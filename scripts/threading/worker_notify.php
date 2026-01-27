<?php
/**
 * SCD Lab - Multithreading Concept (PHP Version)
 *
 * Worker 2: Email / notification sender (simulated)
 *
 * This file is the second "thread-like" worker. It behaves similarly to
 * worker_log.php but represents a different task in the application:
 *
 * - Imagine it sending booking confirmation emails / notifications.
 * - For the demo it simply appends lines to the same shared.log file.
 *
 * Each line looks like:
 *   [timestamp] [worker_notify] step=X mode=lock|nolock
 *
 * Together with worker_log.php this demonstrates:
 * - Two concurrent activities (multi-threading concept)
 * - Writing to a shared resource with and without synchronization
 */

if (php_sapi_name() !== 'cli') {
    fwrite(STDERR, "worker_notify.php is CLI only.\n");
    exit(1);
}

$mode = $argv[1] ?? 'nolock';
$mode = strtolower($mode) === 'lock' ? 'lock' : 'nolock';

$workerName   = 'worker_notify';
$steps        = 10;
$baseDir      = __DIR__; // C:\xampp\htdocs\scd\scripts\threading
$sharedLog    = $baseDir . DIRECTORY_SEPARATOR . 'shared.log';
$sharedLock   = $baseDir . DIRECTORY_SEPARATOR . 'shared.lock';

for ($i = 1; $i <= $steps; $i++) {
    $timestamp = date('Y-m-d H:i:s');
    $line = sprintf(
        "%s | %s | step=%02d | mode=%s%s",
        $timestamp,
        $workerName,
        $i,
        $mode,
        PHP_EOL
    );

    if ($mode === 'lock') {
        // See worker_log.php for detailed Java-synchronized style comments
        $lockHandle = fopen($sharedLock, 'c');
        if ($lockHandle === false) {
            fwrite(STDERR, "[$workerName] Could not open lock file.\n");
            exit(1);
        }

        if (flock($lockHandle, LOCK_EX)) {
            $logHandle = fopen($sharedLog, 'ab');
            if ($logHandle !== false) {
                fwrite($logHandle, $line);
                fflush($logHandle);
                fclose($logHandle);
            }
            flock($lockHandle, LOCK_UN);
        }

        fclose($lockHandle);
    } else {
        $logHandle = fopen($sharedLog, 'ab');
        if ($logHandle !== false) {
            fwrite($logHandle, $line);
            fflush($logHandle);
            fclose($logHandle);
        }
    }

    usleep(random_int(20_000, 120_000));
}

exit(0);

