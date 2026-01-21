<?php
/**
 * CoCode Automation Script
 * Launches the PHP internal server and opens the browser.
 */

// Configuration
$host = '127.0.0.1';
$port = 8000;

$entryPoint = 'login.php'; 
$url = "http://$host:$port/$entryPoint";

// open the url with default web browser based on OS
// we first need to detect the OS
$os = strtoupper(substr(PHP_OS, 0, 3));
$cmd = '';

echo "--------------------------------------------------\n";
echo "Starting CoCode Development Environment...\n";
echo "Platform: " . PHP_OS . "\n";
echo "Target: $url\n";
echo "--------------------------------------------------\n\n";

if ($os === 'WIN') {
    // Windows: 'start' command opens the default browser
    pclose(popen("start \"\" \"$url\"", "r")); 
} elseif ($os === 'DAR') {
    // macOS (Darwin)
    pclose(popen("open \"$url\"", "r"));
} else {
    // Linux
    pclose(popen("xdg-open \"$url\"", "r"));
}

// 2. Start the PHP Built-in Server
// This command blocks execution (runs continuously) until you close the window
// -t . tells PHP to serve the current directory
// -S starts the server
passthru("php -S $host:$port -t .");
?>