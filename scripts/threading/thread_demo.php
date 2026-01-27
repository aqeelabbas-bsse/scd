<?php
/**
 * SCD Lab - Multithreading + Synchronization Demo (PHP CLI)
 *
 * This script is the main "Thread Runner" for the WanderLust SCD project.
 * It is designed to mirror the Java examples from your SCD slides:
 *
 * - In Java: you create Thread/Runnable classes and start them with start()
 * - Here:   we create two separate PHP CLI processes (workers) and run them
 *           concurrently using proc_open(), similar to starting two threads.
 *
 * It demonstrates:
 *  - Two concurrent "tasks": worker_log + worker_notify
 *  - Shared resource: shared.log (plus shared.lock for synchronization)
 *  - Mode "nolock":  writes without flock()  -> potential interleaving
 *  - Mode "lock":    writes with flock()     -> synchronized / ordered
 *
 * Usage from project root (VS Code terminal / CMD):
 *   php scripts/threading/thread_demo.php nolock
 *   php scripts/threading/thread_demo.php lock
 */

if (php_sapi_name() !== 'cli') {
    fwrite(STDERR, "thread_demo.php is CLI only.\n");
    exit(1);
}

// ------------- CLI ARGUMENT PARSING -------------

$mode = $argv[1] ?? '';
if (!in_array(strtolower($mode), ['lock', 'nolock'], true)) {
    fwrite(STDERR, "Usage: php scripts/threading/thread_demo.php [lock|nolock]\n");
    exit(1);
}
$mode = strtolower($mode);

$baseDir     = __DIR__; // C:\xampp\htdocs\scd\scripts\threading
$sharedLog   = $baseDir . DIRECTORY_SEPARATOR . 'shared.log';
$sharedLock  = $baseDir . DIRECTORY_SEPARATOR . 'shared.lock';

// Clear previous log (per run) and note mode / timestamp
@unlink($sharedLog);
@touch($sharedLog);

$header = sprintf(
    "=== THREAD DEMO START (%s mode) at %s ===%s",
    strtoupper($mode),
    date('Y-m-d H:i:s'),
    PHP_EOL
);
file_put_contents($sharedLog, $header, FILE_APPEND);

// Ensure lock file exists (used by workers)
@touch($sharedLock);

echo "==========================================================" . PHP_EOL;
echo "SCD Multithreading Demo - Mode: " . strtoupper($mode) . PHP_EOL;
echo "Project: WanderLust Pakistan (Tours & Hospitality)" . PHP_EOL;
echo "Shared log file : {$sharedLog}" . PHP_EOL;
echo "Shared lock file: {$sharedLock}" . PHP_EOL;
echo "==========================================================" . PHP_EOL;

// ------------- START "THREAD-LIKE" WORKERS -------------

/**
 * Java analogy:
 *
 *   ThreadDemo R1 = new ThreadDemo("Thread-1");
 *   R1.start();
 *   ThreadDemo R2 = new ThreadDemo("Thread-2");
 *   R2.start();
 *
 * PHP analogy:
 *   - Each worker is a separate PHP CLI process.
 *   - proc_open() is used to start them concurrently.
 *   - We then wait for both processes to finish.
 */

$phpBinary = PHP_BINARY; // Path to current php.exe (XAMPP) - safe for Windows

// Escape paths for shell (Windows friendly)
$workerLog    = escapeshellarg($baseDir . DIRECTORY_SEPARATOR . 'worker_log.php');
$workerNotify = escapeshellarg($baseDir . DIRECTORY_SEPARATOR . 'worker_notify.php');
$phpEscaped   = escapeshellarg($phpBinary);

$modeArg = $mode; // "lock" or "nolock" (already validated)

$cmd1 = $phpEscaped . ' ' . $workerLog . ' ' . $modeArg;
$cmd2 = $phpEscaped . ' ' . $workerNotify . ' ' . $modeArg;

$descriptorspec = [
    0 => ['pipe', 'r'], // stdin
    1 => ['pipe', 'w'], // stdout
    2 => ['pipe', 'w'], // stderr
];

echo "Starting worker processes..." . PHP_EOL;

$processes = [];

$pipes1 = [];
$proc1  = proc_open($cmd1, $descriptorspec, $pipes1, $baseDir);
if (is_resource($proc1)) {
    $processes[] = ['name' => 'worker_log', 'proc' => $proc1, 'pipes' => $pipes1];
} else {
    fwrite(STDERR, "Failed to start worker_log process.\n");
}

$pipes2 = [];
$proc2  = proc_open($cmd2, $descriptorspec, $pipes2, $baseDir);
if (is_resource($proc2)) {
    $processes[] = ['name' => 'worker_notify', 'proc' => $proc2, 'pipes' => $pipes2];
} else {
    fwrite(STDERR, "Failed to start worker_notify process.\n");
}

// Close all pipes (we don't need direct worker output, they write to shared.log)
foreach ($processes as &$p) {
    foreach ($p['pipes'] as $pipe) {
        if (is_resource($pipe)) {
            fclose($pipe);
        }
    }
}
unset($p);

// ------------- WAIT FOR WORKERS TO FINISH -------------

echo "Waiting for workers to finish..." . PHP_EOL;

$running = true;
while ($running) {
    $running = false;
    foreach ($processes as &$p) {
        if (!is_resource($p['proc'])) {
            continue;
        }
        $status = proc_get_status($p['proc']);
        if ($status['running']) {
            $running = true;
        } else {
            // Process finished, close it
            proc_close($p['proc']);
            $p['proc'] = null;
        }
    }
    unset($p);

    usleep(50_000); // 50 ms
}

echo "Both workers completed." . PHP_EOL;

// ------------- ANALYZE SHARED LOG -------------

$lines = [];
if (file_exists($sharedLog)) {
    $lines = file($sharedLog, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
}

$totalLines    = 0;
$workerCounts  = [
    'worker_log'    => 0,
    'worker_notify' => 0,
];

foreach ($lines as $line) {
    // Skip header line(s) that do not contain worker name
    if (strpos($line, 'worker_log') === false && strpos($line, 'worker_notify') === false) {
        continue;
    }

    $totalLines++;

    if (strpos($line, 'worker_log') !== false) {
        $workerCounts['worker_log']++;
    }
    if (strpos($line, 'worker_notify') !== false) {
        $workerCounts['worker_notify']++;
    }
}

// ------------- SUMMARY (JUnit-style) -------------

echo PHP_EOL;
echo "==================== SUMMARY ====================" . PHP_EOL;
echo "Mode         : " . strtoupper($mode) . PHP_EOL;
echo "Total events : " . $totalLines . PHP_EOL;
echo "worker_log   : " . $workerCounts['worker_log'] . " entries" . PHP_EOL;
echo "worker_notify: " . $workerCounts['worker_notify'] . " entries" . PHP_EOL;
echo "Log file     : " . $sharedLog . PHP_EOL;
echo "Lock file    : " . $sharedLock . PHP_EOL;
echo "================================================" . PHP_EOL;

/**
 * For viva:
 * - Run in nolock mode -> open shared.log to show interleaving of workers.
 * - Run in lock mode   -> show more consistent/non-overlapping writes,
 *   with each line acquired inside a synchronized (flock) section.
 */

exit(0);

