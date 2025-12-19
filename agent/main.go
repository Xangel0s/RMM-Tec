package main

import (
	"bufio"
	"bytes"
	"crypto/sha256"
	"encoding/hex"
	"encoding/json"
	"fmt"
	"io"
	"log"
	"net/http"
	"os"
	"os/exec"
	"path/filepath"
	"regexp"
	"strconv"
	"strings"
	"sync"
	"time"

	"github.com/shirou/gopsutil/v3/cpu"
	"github.com/shirou/gopsutil/v3/disk"
	"github.com/shirou/gopsutil/v3/mem"
	"github.com/shirou/gopsutil/v3/process"
)

type HeartbeatPayload struct {
	Hostname        string                 `json:"hostname"`
	ApiToken        string                 `json:"api_token"`
	LocalIP         string                 `json:"local_ip"`
	PublicIP        string                 `json:"public_ip"`
	OSInfo          string                 `json:"os_info"`
	HardwareSummary map[string]interface{} `json:"hardware_summary"`
	RustDeskID      string                 `json:"rustdesk_id"`
}

type Command struct {
	ID      int    `json:"id"`
	Command string `json:"command"`
}

type CommandResult struct {
	Status string `json:"status"`
	Output string `json:"output"`
}

const (
	serverBaseURL = "http://127.0.0.1:8085/api"
	apiToken      = "test-token-123"
	ConfigPath    = "C:\\mapeo_config\\config.json"
	DBPath        = "C:\\mapeo_config\\filesystem_db.json"
)

// --- Error Codes ---
const (
	ERR_SUCCESS        = "0"
	ERR_FILE_NOT_FOUND = "1001"
	ERR_ACCESS_DENIED  = "1002"
	ERR_INVALID_PATH   = "1003"
	ERR_CONFIG_LOAD    = "2001"
	ERR_CONFIG_PARSE   = "2002"
	ERR_DB_LOAD        = "2003"
	ERR_DB_SAVE        = "2004"
	ERR_INTERNAL       = "5000"
)

// --- Configuration ---
type AgentConfig struct {
	Version   string   `json:"version"`
	BasePaths []string `json:"base_paths"`
	Settings  struct {
		LogLevel          string `json:"log_level"`
		CacheTTLSeconds   int    `json:"cache_ttl_seconds"`
		PollingIntervalMs int    `json:"polling_interval_ms"`
	} `json:"settings"`
}

var currentConfig AgentConfig

// --- File System Database ---
type FileEntry struct {
	Name    string `json:"name"`
	IsDir   bool   `json:"is_dir"`
	Size    int64  `json:"size"`
	ModTime string `json:"mod_time"`
}

type CachedDir struct {
	Path        string      `json:"path"`
	Files       []FileEntry `json:"files"`
	LastUpdated time.Time   `json:"last_updated"`
	Hash        string      `json:"hash"` // Checksum for integrity
}

type FileSystemDB struct {
	Version     string               `json:"version"`
	LastScanned time.Time            `json:"last_scanned"`
	Data        map[string]CachedDir `json:"data"`
}

var (
	fsDB  FileSystemDB
	dbMux sync.Mutex
)

const CacheTTL = 30 * time.Second // Default, will be updated from config

func main() {
	setupLogger()
	log.Println("Starting RMM Agent (Telemetry + Remote Execution)...")

	// 0. Load Configuration
	start := time.Now()
	err := loadConfiguration()
	elapsed := time.Since(start)
	if err != nil {
		log.Printf("ERROR: Failed to load configuration: %v. Using defaults.\n", err)
		// Set defaults if load fails
		currentConfig.BasePaths = []string{`C:\Drivers`}
		currentConfig.Settings.PollingIntervalMs = 200
	} else {
		log.Printf("INFO: Configuration loaded in %s. Version: %s\n", elapsed, currentConfig.Version)
	}

	// 0.5 Load File System DB
	loadFileSystemDB()

	// 1. System Initialization & Pre-mapping
	initializeFileSystem()

	// Start background re-indexing
	go backgroundIndexer()

	hostname, _ := os.Hostname()

	go pollCommands() // Iniciar polling de comandos en segundo plano

	for {
		// 1. Recopilar Datos Reales
		v, _ := mem.VirtualMemory()
		c, _ := cpu.Percent(time.Second, false)
		d, _ := disk.Usage("/")

		cpuUsage := 0.0
		if len(c) > 0 {
			cpuUsage = c[0]
		}

		// Datos del hardware
		hardware := map[string]interface{}{
			"cpu_usage_percent": fmt.Sprintf("%.2f", cpuUsage),
			"ram_total_gb":      fmt.Sprintf("%.2f", float64(v.Total)/1024/1024/1024),
			"ram_used_percent":  fmt.Sprintf("%.2f", v.UsedPercent),
			"disk_total_gb":     fmt.Sprintf("%.2f", float64(d.Total)/1024/1024/1024),
			"disk_used_percent": fmt.Sprintf("%.2f", d.UsedPercent),
		}

		// Try to find RustDesk ID
		rustDeskID := getRustDeskID()
		if rustDeskID == "" {
			rustDeskID = "Not Found"
		}

		payload := HeartbeatPayload{
			Hostname:        hostname,
			ApiToken:        apiToken,
			LocalIP:         "127.0.0.1",
			PublicIP:        "8.8.8.8",
			OSInfo:          "Windows 11",
			HardwareSummary: hardware,
			RustDeskID:      rustDeskID,
		}

		// 2. Enviar Heartbeat
		sendHeartbeat(payload)

		// Dormir según configuración
		interval := time.Duration(currentConfig.Settings.PollingIntervalMs) * time.Millisecond
		if interval == 0 {
			interval = 200 * time.Millisecond
		}
		time.Sleep(60 * time.Second) // Heartbeat interval (keep at 60s for now)
	}
}

func setupLogger() {
	logFile, err := os.OpenFile("agent_execution.log", os.O_CREATE|os.O_APPEND|os.O_WRONLY, 0644)
	if err != nil {
		fmt.Println("Error opening log file:", err)
		return
	}
	// MultiWriter to print to console AND file
	mw := io.MultiWriter(os.Stdout, logFile)
	log.SetOutput(mw)
	log.SetPrefix(fmt.Sprintf("[%s] ", time.Now().Format("2006-01-02 15:04:05")))
	log.SetFlags(0) // No default flags, we handle timestamp in prefix (or use log.LstdFlags)
}

func loadConfiguration() error {
	// Check existence
	if _, err := os.Stat(ConfigPath); os.IsNotExist(err) {
		return fmt.Errorf("config file not found at %s", ConfigPath)
	}

	// Read file
	data, err := os.ReadFile(ConfigPath)
	if err != nil {
		return err
	}

	// Parse JSON
	err = json.Unmarshal(data, &currentConfig)
	if err != nil {
		return err
	}

	// Basic validation
	if len(currentConfig.BasePaths) == 0 {
		return fmt.Errorf("no base_paths defined in config")
	}

	return nil
}

func sendHeartbeat(payload HeartbeatPayload) {
	jsonData, err := json.Marshal(payload)
	if err != nil {
		fmt.Println("Error marshalling heartbeat:", err)
		return
	}

	resp, err := http.Post(serverBaseURL+"/heartbeat", "application/json", bytes.NewBuffer(jsonData))
	if err != nil {
		fmt.Println("Error sending heartbeat:", err)
		return
	}
	defer resp.Body.Close()
	fmt.Println("Heartbeat sent, status:", resp.Status)
}

func pollCommands() {
	for {
		// Consultar comandos pendientes
		resp, err := http.Get(fmt.Sprintf("%s/commands/pending?api_token=%s", serverBaseURL, apiToken))
		if err != nil {
			fmt.Println("Error checking commands:", err)
			time.Sleep(5 * time.Second)
			continue
		}

		if resp.StatusCode == 200 {
			var commands []Command
			body, _ := io.ReadAll(resp.Body)
			json.Unmarshal(body, &commands)
			resp.Body.Close()

			for _, cmd := range commands {
				fmt.Printf("Executing command [%d]: %s\n", cmd.ID, cmd.Command)
				output, status := executeCommand(cmd.Command)
				reportCommandResult(cmd.ID, status, output)
			}
		} else {
			resp.Body.Close()
		}

		time.Sleep(100 * time.Millisecond) // Check every 100ms for "instant" feel
	}
}

func executeCommand(commandStr string) (string, string) {
	// Remove BOM and other invisible characters
	commandStr = strings.Trim(commandStr, "\uFEFF")
	commandStr = strings.TrimSpace(commandStr)

	// Debug logging with byte dump to find hidden chars
	fmt.Printf("DEBUG: Processing command: '%s' (Hex: %x)\n", commandStr, []byte(commandStr))

	// Comandos especiales
	if commandStr == "__GET_PROCESSES__" {
		return getProcesses()
	}

	if strings.HasPrefix(commandStr, "__LIST_DIRECTORY__") {
		path := strings.TrimPrefix(commandStr, "__LIST_DIRECTORY__")
		path = strings.TrimSpace(path)
		// Remove quotes if present
		path = strings.Trim(path, "\"'")
		if path == "" {
			path = "C:\\"
		}

		// Fix: "C:" is treated as relative to CWD on Windows. Force absolute path.
		if len(path) == 2 && path[1] == ':' {
			path = path + "\\"
		}

		// Security: Prevent accessing CWD by accident if path is not absolute
		if !filepath.IsAbs(path) {
			fmt.Printf("DEBUG: Path '%s' is not absolute. Forcing root.\n", path)
			path = "C:\\"
		}

		// Advanced Path Sanitization to handle duplicate segments (e.g. C:\Drivers\Drivers)
		path = sanitizePath(path)

		return listDirectory(path)
	}

	if strings.HasPrefix(commandStr, "__KILL_PROCESS__") {
		pidStr := strings.TrimPrefix(commandStr, "__KILL_PROCESS__")
		pidStr = strings.TrimSpace(pidStr)
		return killProcess(pidStr)
	}

	if strings.HasPrefix(commandStr, "__DOWNLOAD_FILE__") {
		// Regex to parse args: matches quoted strings or non-space sequences
		re := regexp.MustCompile(`"([^"]+)"|(\S+)`)
		matches := re.FindAllStringSubmatch(commandStr, -1)

		// matches[0] is __DOWNLOAD_FILE__
		if len(matches) >= 3 {
			url := matches[1][1] // Group 1 (quoted)
			if url == "" {
				url = matches[1][2]
			} // Group 2 (unquoted)

			dest := matches[2][1]
			if dest == "" {
				dest = matches[2][2]
			}

			return downloadFile(url, dest)
		}
		return "Invalid arguments for __DOWNLOAD_FILE__", "failed"
	}

	// Safety check: Do not pass internal commands to shell
	if strings.HasPrefix(commandStr, "__") {
		return fmt.Sprintf("Error: Unknown internal command '%s'", commandStr), "failed"
	}

	// Ejecutar en Powershell para Windows
	cmd := exec.Command("powershell", "-Command", commandStr)

	// Para Linux sería: exec.Command("sh", "-c", commandStr)

	var out bytes.Buffer
	var stderr bytes.Buffer
	cmd.Stdout = &out
	cmd.Stderr = &stderr

	err := cmd.Run()
	output := out.String()
	if stderr.String() != "" {
		output += "\nERROR:\n" + stderr.String()
	}

	if err != nil {
		return output, "failed"
	}
	return output, "completed"
}

func getProcesses() (string, string) {
	procs, err := process.Processes()
	if err != nil {
		return fmt.Sprintf("Error getting processes: %v", err), "failed"
	}

	var result []map[string]interface{}
	for _, p := range procs {
		n, err := p.Name()
		if err != nil {
			n = "Unknown"
		}

		u, err := p.Username()
		if err != nil {
			u = "-"
		}

		// Memory in MB
		memInfo, err := p.MemoryInfo()
		memStr := "-"
		if err == nil {
			mb := float64(memInfo.RSS) / 1024 / 1024
			memStr = fmt.Sprintf("%.1f MB", mb)
		}

		c, _ := p.CPUPercent()

		result = append(result, map[string]interface{}{
			"pid":    p.Pid,
			"name":   n,
			"user":   u,
			"memory": memStr,
			"cpu":    fmt.Sprintf("%.2f", c),
		})
	}

	// Limit to top 100 or something if too large?
	// JSON marshaling might be large.
	jsonData, err := json.Marshal(result)
	if err != nil {
		return fmt.Sprintf("Error marshaling processes: %v", err), "failed"
	}
	return string(jsonData), "completed"
}

func sanitizePath(inputPath string) string {
	// Basic clean
	path := filepath.Clean(inputPath)

	// If it exists, we are good
	if _, err := os.Stat(path); err == nil {
		return path
	}

	// Optimization: Fast check for simple tail duplication (e.g. C:\Drivers\Drivers)
	// This avoids the slow iterative reconstruction loop for the most common error case.
	parent := filepath.Dir(path)
	if _, err := os.Stat(parent); err == nil {
		// Parent exists. Check if the last part is a duplicate of the parent's last part.
		if strings.EqualFold(filepath.Base(path), filepath.Base(parent)) {
			fmt.Printf("DEBUG: Fast correction path from %s to %s\n", path, parent)
			return parent
		}
	}

	// Detect duplications like "Folder\Folder" where the second Folder doesn't exist
	parts := strings.Split(path, string(os.PathSeparator))
	var validParts []string

	// Handle Volume (e.g. C:)
	if len(parts) > 0 && strings.Contains(parts[0], ":") {
		validParts = append(validParts, parts[0]+string(os.PathSeparator))
		parts = parts[1:]
	} else if len(parts) > 0 && parts[0] == "" {
		// Unix root /
		validParts = append(validParts, string(os.PathSeparator))
		parts = parts[1:]
	}

	currentPath := filepath.Join(validParts...)

	for _, part := range parts {
		if part == "" {
			continue
		}

		nextPath := filepath.Join(currentPath, part)
		if _, err := os.Stat(nextPath); err == nil {
			// Part exists, append it
			currentPath = nextPath
			validParts = append(validParts, part)
		} else {
			// Part does not exist.
			// Check if it's a duplicate of the last added part
			if len(validParts) > 0 {
				lastPart := validParts[len(validParts)-1]
				lastPartBase := filepath.Base(lastPart)
				// Case insensitive check for Windows
				if strings.EqualFold(lastPartBase, part) {
					// It's a duplicate and doesn't exist -> Skip it!
					fmt.Printf("DEBUG: Skipping duplicate path segment: %s\n", part)
					continue
				}
			}
			// If it's not a duplicate, append it (it will fail later, but that's correct behavior for non-duplicate bad paths)
			currentPath = nextPath
		}
	}

	return currentPath
}

func listDirectory(path string) (string, string) {
	// 1. Check DB (In-Memory)
	dbMux.Lock()
	if cachedDir, found := fsDB.Data[path]; found {
		// Validar TTL si es necesario, pero aquí el usuario quiere "base de datos centralizada".
		// Asumiremos que el indexador de fondo la mantiene fresca, pero si es muy vieja (ej > 5 min), forzamos lectura.
		if time.Since(cachedDir.LastUpdated) < 5*time.Minute {
			dbMux.Unlock()
			log.Printf("DEBUG: DB HIT for %s", path)

			// Convert CachedDir to the expected JSON format
			fileList := make([]map[string]interface{}, len(cachedDir.Files))
			for i, f := range cachedDir.Files {
				fileList[i] = map[string]interface{}{
					"name":     f.Name,
					"is_dir":   f.IsDir,
					"size":     f.Size,
					"mod_time": f.ModTime,
				}
			}

			jsonData, _ := json.Marshal(map[string]interface{}{
				"path":  path,
				"files": fileList,
			})
			return string(jsonData), "completed"
		}
	}
	dbMux.Unlock()

	// 2. Read from Disk (Cache Miss or Expired)
	// Initialize as empty slice to ensure JSON is "[]" not "null"
	fileList := []map[string]interface{}{}
	var dbFiles []FileEntry

	files, err := os.ReadDir(path)
	if err != nil {
		return fmt.Sprintf("Error reading directory: %v", err), "failed"
	}

	for _, file := range files {
		// Filter out system files/directories
		name := file.Name()
		if name == "$Recycle.Bin" || name == "System Volume Information" || name == "Recovery" ||
			name == "pagefile.sys" || name == "hiberfil.sys" || name == "swapfile.sys" || name == "DumpStack.log.tmp" {
			continue
		}

		info, err := file.Info()
		if err != nil {
			continue
		}

		modTime := info.ModTime().Format("2006-01-02 15:04:05")

		// For JSON response
		fileList = append(fileList, map[string]interface{}{
			"name":     name,
			"is_dir":   file.IsDir(),
			"size":     info.Size(),
			"mod_time": modTime,
		})

		// For DB Storage
		dbFiles = append(dbFiles, FileEntry{
			Name:    name,
			IsDir:   file.IsDir(),
			Size:    info.Size(),
			ModTime: modTime,
		})
	}

	// 3. Update DB
	updateDBEntry(path, dbFiles)

	jsonData, err := json.Marshal(map[string]interface{}{
		"path":  path,
		"files": fileList,
	})
	if err != nil {
		return fmt.Sprintf("Error marshaling file list: %v", err), "failed"
	}

	return string(jsonData), "completed"
}

// --- DB Functions ---

func loadFileSystemDB() {
	dbMux.Lock()
	defer dbMux.Unlock()

	// Init map if empty
	fsDB.Data = make(map[string]CachedDir)

	if _, err := os.Stat(DBPath); os.IsNotExist(err) {
		log.Println("INFO: No existing DB found. Starting fresh.")
		return
	}

	data, err := os.ReadFile(DBPath)
	if err != nil {
		log.Printf("ERROR: Failed to read DB: %v\n", err)
		return
	}

	err = json.Unmarshal(data, &fsDB)
	if err != nil {
		log.Printf("ERROR: Failed to parse DB: %v\n", err)
		// Fallback to empty
		fsDB.Data = make(map[string]CachedDir)
		return
	}
	log.Printf("INFO: Loaded FileSystem DB with %d entries. Last scanned: %s\n", len(fsDB.Data), fsDB.LastScanned)
}

func saveFileSystemDB() {
	dbMux.Lock()
	defer dbMux.Unlock()

	data, err := json.MarshalIndent(fsDB, "", "  ")
	if err != nil {
		log.Printf("ERROR: Failed to marshal DB: %v\n", err)
		return
	}

	err = os.WriteFile(DBPath, data, 0644)
	if err != nil {
		log.Printf("ERROR: Failed to write DB: %v\n", err)
	}
}

func updateDBEntry(path string, files []FileEntry) {
	dbMux.Lock()
	defer dbMux.Unlock()

	// Calculate hash for integrity
	h := sha256.New()
	for _, f := range files {
		h.Write([]byte(f.Name))
		h.Write([]byte(fmt.Sprintf("%d", f.Size)))
		h.Write([]byte(f.ModTime))
	}
	hash := hex.EncodeToString(h.Sum(nil))

	fsDB.Data[path] = CachedDir{
		Path:        path,
		Files:       files,
		LastUpdated: time.Now(),
		Hash:        hash,
	}

	// Trigger save in background (simple debounce could be added here)
	go func() {
		// Wait a bit to batch updates if many happen at once
		time.Sleep(2 * time.Second)
		saveFileSystemDB()
	}()
}

func backgroundIndexer() {
	for {
		log.Println("INFO: Starting background filesystem indexing...")
		fsDB.LastScanned = time.Now()

		paths := currentConfig.BasePaths
		if len(paths) == 0 {
			paths = []string{`C:\Drivers`}
		}

		for _, root := range paths {
			// Recursive walk
			filepath.WalkDir(root, func(path string, d os.DirEntry, err error) error {
				if err != nil {
					return nil
				}
				if d.IsDir() {
					// We only index the directory content (list of files), so we trigger a read
					// But calling listDirectory here would be circular if we are not careful.
					// We just need to simulate "ReadDir" and updateDBEntry.
					// Actually, let's just do it manually to be efficient.

					entries, err := os.ReadDir(path)
					if err == nil {
						var dbFiles []FileEntry
						for _, e := range entries {
							info, _ := e.Info()
							if info != nil {
								dbFiles = append(dbFiles, FileEntry{
									Name:    e.Name(),
									IsDir:   e.IsDir(),
									Size:    info.Size(),
									ModTime: info.ModTime().Format("2006-01-02 15:04:05"),
								})
							}
						}
						updateDBEntry(path, dbFiles)
					}
				}
				return nil
			})
		}

		log.Println("INFO: Background indexing complete.")
		time.Sleep(5 * time.Minute) // Re-scan every 5 minutes
	}
}

func initializeFileSystem() {
	log.Println("INFO: Initializing file system structure from configuration...")

	basePaths := currentConfig.BasePaths
	// Fallback if empty (should be caught by loadConfiguration but safe to have)
	if len(basePaths) == 0 {
		basePaths = []string{`C:\Drivers`}
	}

	for _, path := range basePaths {
		if _, err := os.Stat(path); os.IsNotExist(err) {
			log.Printf("INFO: Creating missing directory: %s\n", path)
			err := os.MkdirAll(path, 0755)
			if err != nil {
				log.Printf("ERROR: Failed to create %s: %v\n", path, err)
			}
		}
	}
	log.Println("INFO: File system initialization complete.")
}

func killProcess(pidStr string) (string, string) {
	pid, err := strconv.Atoi(pidStr)
	if err != nil {
		return "Invalid PID", "failed"
	}

	p, err := process.NewProcess(int32(pid))
	if err != nil {
		return fmt.Sprintf("Process not found: %v", err), "failed"
	}

	err = p.Kill()
	if err != nil {
		return fmt.Sprintf("Failed to kill process: %v", err), "failed"
	}

	return fmt.Sprintf("Process %d killed successfully", pid), "completed"
}

func reportCommandResult(id int, status string, output string) {
	result := CommandResult{
		Status: status,
		Output: output,
	}
	jsonData, _ := json.Marshal(result)

	url := fmt.Sprintf("%s/commands/%d/result", serverBaseURL, id)
	resp, err := http.Post(url, "application/json", bytes.NewBuffer(jsonData))
	if err != nil {
		fmt.Println("Error reporting command result:", err)
		return
	}
	defer resp.Body.Close()
	fmt.Println("Command reported:", status)
}

func downloadFile(url string, dest string) (string, string) {
	fmt.Printf("DEBUG: Downloading %s to %s\n", url, dest)

	// Create the file
	out, err := os.Create(dest)
	if err != nil {
		return fmt.Sprintf("Error creating file: %v", err), "failed"
	}
	defer out.Close()

	// Get the data
	resp, err := http.Get(url)
	if err != nil {
		return fmt.Sprintf("Error downloading file: %v", err), "failed"
	}
	defer resp.Body.Close()

	// Check server response
	if resp.StatusCode != http.StatusOK {
		return fmt.Sprintf("Bad status: %s", resp.Status), "failed"
	}

	// Writer the body to file
	_, err = io.Copy(out, resp.Body)
	if err != nil {
		log.Printf("ERROR: Error saving file %s: %v", dest, err)
		return fmt.Sprintf("Error saving file: %v", err), "failed"
	}

	log.Printf("INFO: File downloaded successfully to %s", dest)
	return fmt.Sprintf("File downloaded successfully to %s", dest), "completed"
}

func getRustDeskID() string {
	// Common paths for RustDesk config
	// 1. User AppData
	appData, err := os.UserConfigDir()
	if err == nil {
		path := filepath.Join(appData, "RustDesk", "config", "RustDesk2.toml")
		if id := extractIDFromToml(path); id != "" {
			return id
		}
		path = filepath.Join(appData, "RustDesk", "config", "RustDesk.toml")
		if id := extractIDFromToml(path); id != "" {
			return id
		}
	}

	// 2. Service AppData (System) - Hardcoded for typical Windows install
	servicePath := `C:\Windows\ServiceProfiles\LocalService\AppData\Roaming\RustDesk\config\RustDesk2.toml`
	if id := extractIDFromToml(servicePath); id != "" {
		return id
	}
	servicePath = `C:\Windows\ServiceProfiles\LocalService\AppData\Roaming\RustDesk\config\RustDesk.toml`
	if id := extractIDFromToml(servicePath); id != "" {
		return id
	}

	return ""
}

func extractIDFromToml(path string) string {
	file, err := os.Open(path)
	if err != nil {
		return ""
	}
	defer file.Close()

	scanner := bufio.NewScanner(file)
	for scanner.Scan() {
		line := strings.TrimSpace(scanner.Text())
		// Look for: id = '123456' or id = "123456"
		if strings.HasPrefix(line, "id =") {
			parts := strings.Split(line, "=")
			if len(parts) == 2 {
				id := strings.TrimSpace(parts[1])
				id = strings.Trim(id, "'\"")
				return id
			}
		}
	}
	return ""
}
