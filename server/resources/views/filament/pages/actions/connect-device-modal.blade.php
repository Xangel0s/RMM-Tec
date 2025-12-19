<div class="p-4 space-y-4">
    <div class="bg-gray-100 dark:bg-gray-800 p-4 rounded-lg">
        <h3 class="font-bold text-lg mb-2">1. Download Agent</h3>
        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
            Download the latest agent installer for your operating system.
        </p>
        <div class="flex gap-2">
            <a href="#" class="px-3 py-1 bg-blue-600 text-white rounded text-sm hover:bg-blue-700">Windows (.exe)</a>
            <a href="#" class="px-3 py-1 bg-gray-600 text-white rounded text-sm hover:bg-gray-700">Linux (.sh)</a>
        </div>
    </div>

    <div class="bg-gray-100 dark:bg-gray-800 p-4 rounded-lg">
        <h3 class="font-bold text-lg mb-2">2. Install & Register</h3>
        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
            Run the following command in PowerShell (Admin) or Terminal:
        </p>
        <div class="relative group">
            <pre class="bg-black text-green-400 p-3 rounded text-xs font-mono overflow-x-auto">
./agent-install.exe --server="{{ config('app.url') }}" --token="YOUR_API_TOKEN"</pre>
            <button onclick="navigator.clipboard.writeText(this.previousElementSibling.innerText)" 
                class="absolute top-2 right-2 text-gray-400 hover:text-white bg-gray-700 p-1 rounded">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
            </button>
        </div>
    </div>

    <div class="text-sm text-gray-500 italic">
        Note: The device will appear in the dashboard automatically once the agent starts.
    </div>
</div>