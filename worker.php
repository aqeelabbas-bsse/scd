<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once __DIR__ . "/db.php";                 // must provide $conn (mysqli)
require_once __DIR__ . "/includes/invoice.php";   // generateInvoice()
require_once __DIR__ . "/includes/mailer.php";    // sendBookingConfirmationEmail()

echo "Worker started...\n";

while (true) {

    // pick 1 pending job
    $job = $conn->query("SELECT * FROM jobs WHERE status='pending' ORDER BY id ASC LIMIT 1")->fetch_assoc();

    if (!$job) {
        sleep(2);
        continue;
    }

    $jobId = (int)$job['id'];

    // mark processing
    $conn->query("UPDATE jobs SET status='processing' WHERE id={$jobId}");

    try {
        $booking = json_decode($job['payload'], true);
        if (!$booking) {
            throw new Exception("Invalid JSON payload");
        }

        $bookingId = (int)($booking['booking_id'] ?? 0);
        if ($bookingId <= 0) {
            throw new Exception("Missing booking_id");
        }

        // 1) generate invoice file
        $invoicePath = generateInvoice($booking, $bookingId);

        // 2) send email with attachment
        sendBookingConfirmationEmail($booking, $invoicePath);

        // done
        $conn->query("UPDATE jobs SET status='done', processed_at=NOW(), error_message=NULL WHERE id={$jobId}");
        echo "Job {$jobId} done\n";

    } catch (Throwable $e) {
        $msg = $conn->real_escape_string($e->getMessage());
        $conn->query("UPDATE jobs SET status='failed', processed_at=NOW(), error_message='{$msg}' WHERE id={$jobId}");
        echo "Job {$jobId} failed: " . $e->getMessage() . "\n";
    }
}
