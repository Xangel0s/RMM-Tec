I will modify the `agent/main.go` file to filter out system directories like `$Recycle.Bin` and `System Volume Information` from the file listing. This will prevent them from appearing in the frontend, so users cannot accidentally navigate into them and trigger recursive path errors.

**Plan:**
1.  **Edit `agent/main.go`**:
    *   Locate the `listDirectory` function.
    *   Add a check inside the file iteration loop to skip files/directories with names like `$Recycle.Bin`, `System Volume Information`, `Recovery`, `pagefile.sys`, etc.
2.  **Restart the Agent**:
    *   Stop the currently running agent process in terminal 18.
    *   Start the agent again (`go run main.go`) to apply the changes.
