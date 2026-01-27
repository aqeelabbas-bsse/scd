<?php
/**
 * SCD Lab - Multithreading Concept (PHP Version)
 *
 * Worker 1: Booking log / audit logger
 *
 * This file is part of the multithreading demo for the WanderLust (SCD) project.
 * It is the PHP equivalent of a Java Thread/Runnable from your slides.
 *
 * - In Java you would create a Thread / Runnable class and call start().
 * - Here we create a separate PHP CLI process that runs concurrently.
 *
 * This worker appends lines to a shared log file:
 *   [timestamp] [worker_log] step=X mode=lock|nolock
 *
 * Synchronization:
 * - In "nolock" mode we write WITHOUT flock(), showing possible interleaving.
 * - In "lock" mode we lock a shared monitor file (shared.lock) using flock(LOCK_EX),
 *   which is equivalent to Java's synchronized(monitor) { ... } block.
 *
 * IMPORTANT:
 * - This script is CLI-only and does NOT affect the web routes or DB.
 */

if (php_sapi_name() !== 'cli') {
    // Safety: do not allow HTTP access
    fwrite(STDERR, "worker_log.php is CLI only.\n");
    exit(1);
}

$mode = $argv[1] ?? 'nolock'; // "lock" or "nolock"
$mode = strtolower($mode) === 'lock' ? 'lock' : 'nolock';

$workerName   = 'worker_log';
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
        /**
         * ==============================
         *  WITH SYNCHRONIZATION (LOCK)
         * ==============================
         *
         * Java equivalent:
         *   synchronized(monitorObject) {
         *       // write to shared resource
         *   }
         *
         * PHP:
         *   - We lock a separate monitor file shared.lock using flock(LOCK_EX)
         *   - While one process holds the lock, others must wait
         *   - This prevents race conditions on shared.log
         */

        $lockHandle = fopen($sharedLock, 'c');
        if ($lockHandle === false) {
            fwrite(STDERR, "[$workerName] Could not open lock file.\n");
            exit(1);
        }

        // Acquire exclusive lock (may block until available)
        if (flock($lockHandle, LOCK_EX)) {
            $logHandle = fopen($sharedLog, 'ab');
            if ($logHandle !== false) {
                fwrite($logHandle, $line);
                fflush($logHandle);
                fclose($logHandle);
            }

            // Release lock
            flock($lockHandle, LOCK_UN);
        }

        fclose($lockHandle);
    } else {
        /**
         * ==============================
         *  WITHOUT SYNCHRONIZATION
         * ==============================
         *
         * Java equivalent:
         *   // NO synchronized block
         *   // Multiple threads can write at the same time
         *
         * Here processes can interleave and cause race-like behaviour.
         */
        $logHandle = fopen($sharedLog, 'ab');
        if ($logHandle !== false) {
            fwrite($logHandle, $line);
            fflush($logHandle);
            fclose($logHandle);
        }
    }

    // Small random sleep to simulate work + encourage interleaving
    usleep(random_int(20_000, 120_000)); // 20–120 ms
}

exit(0);

