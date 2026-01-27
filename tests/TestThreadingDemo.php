<?php
/**
 * Optional PHPUnit Test - Multithreading Demo
 *
 * This test verifies the behaviour of the threading demo in LOCK mode
 * without touching any database tables or website routes.
 *
 * It maps to the SCD multithreading slides as follows:
 *  - Starting two worker processes  -> creating two Threads
 *  - join / waiting for completion  -> proc_open + proc_get_status loop
 *  - synchronized block             -> flock(shared.lock) in workers
 */

use PHPUnit\Framework\TestCase;

class TestThreadingDemo extends TestCase
{
    /**
     * Ensures that in LOCK mode both workers write the expected number
     * of entries to the shared log file.
     *
     * Expected:
     *   - worker_log    -> 10 entries
     *   - worker_notify -> 10 entries
     */
    public function testLockModeWritesExpectedLines(): void
    {
        $projectRoot = realpath(__DIR__ . '/..'); // C:\xampp\htdocs\scd
        $threadDemo  = $projectRoot . DIRECTORY_SEPARATOR . 'scripts' . DIRECTORY_SEPARATOR . 'threading' . DIRECTORY_SEPARATOR . 'thread_demo.php';

        if (!file_exists($threadDemo)) {
            $this->markTestSkipped('thread_demo.php not found; threading demo not set up.');
        }

        // Build command using current PHP binary
        $phpBinary = PHP_BINARY;
        $cmd = escapeshellarg($phpBinary) . ' ' . escapeshellarg($threadDemo) . ' lock';

        // Execute the demo in LOCK mode
        $output = [];
        $exitCode = 0;
        exec($cmd, $output, $exitCode);

        $this->assertSame(
            0,
            $exitCode,
            'thread_demo.php failed to execute in LOCK mode'
        );

        $sharedLog = $projectRoot . DIRECTORY_SEPARATOR . 'scripts' . DIRECTORY_SEPARATOR . 'threading' . DIRECTORY_SEPARATOR . 'shared.log';

        $this->assertFileExists(
            $sharedLog,
            'shared.log was not created by thread_demo.php'
        );

        $lines = file($sharedLog, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        $workerCounts = [
            'worker_log'    => 0,
            'worker_notify' => 0,
        ];

        foreach ($lines as $line) {
            // Only count lines written by workers
            if (strpos($line, 'worker_log') !== false) {
                $workerCounts['worker_log']++;
            }
            if (strpos($line, 'worker_notify') !== false) {
                $workerCounts['worker_notify']++;
            }
        }

        // Each worker loops 10 times in LOCK mode
        $this->assertSame(
            10,
            $workerCounts['worker_log'],
            'worker_log did not write 10 entries in LOCK mode'
        );

        $this->assertSame(
            10,
            $workerCounts['worker_notify'],
            'worker_notify did not write 10 entries in LOCK mode'
        );

        $this->assertSame(
            20,
            $workerCounts['worker_log'] + $workerCounts['worker_notify'],
            'Total combined entries in LOCK mode is not equal to 20'
        );
    }
}

