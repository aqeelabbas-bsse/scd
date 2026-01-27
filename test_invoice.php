<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// 1) invoice generator
require_once __DIR__ . "/includes/invoice.php";

// 2) mail sender (PHPMailer)
require_once __DIR__ . "/includes/mailer.php";

// Fake booking data (same keys as your system)
$booking = [
  "product_name" => "Test Glamp - Luxury Tent",
  "full_name"    => "Invoice Test User",
  "email"        => "camer4090@gmail.com",   // ✅ put YOUR real email here for test
  "phone"        => "03001234567",
  "from_date"    => "2026-02-01",
  "to_date"      => "2026-02-05",
  "guests"       => "2",
];

// ✅ Unique ID every run (no overwrite / no cache confusion)
$bookingId = (int)(time() . rand(100, 999));

echo "<h2>TEST: Invoice + Email</h2>";
echo "<b>Booking ID:</b> {$bookingId}<br><br>";

try {
    // 1) Generate invoice file
    $invoicePath = generateInvoice($booking, $bookingId);

    echo "<b>Invoice Path (server):</b><br>";
    echo "<pre>" . htmlspecialchars($invoicePath) . "</pre>";

    echo "<b>File exists?</b> ";
    var_dump(file_exists($invoicePath));
    echo "<br>";

    if (!file_exists($invoicePath)) {
        throw new Exception("Invoice file not created. Check folder: scd/invoices (permissions/path).");
    }

    echo "<b>File size:</b> " . filesize($invoicePath) . " bytes<br>";
    echo "<b>Last modified:</b> " . date('Y-m-d H:i:s', filemtime($invoicePath)) . "<br><br>";

    // 2) Build correct URL dynamically (no hardcode /scd)
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
    $host   = $_SERVER['HTTP_HOST'] ?? "localhost";
    $baseDir = rtrim(dirname($_SERVER['SCRIPT_NAME']), "/\\"); // e.g. /scd or /project/scd
    $invoiceUrl = "{$scheme}://{$host}{$baseDir}/invoices/invoice_{$bookingId}.html?v=" . time();

    echo "<b>Open Invoice in Browser:</b><br>";
    echo "<a href='" . htmlspecialchars($invoiceUrl) . "' target='_blank'>" . htmlspecialchars($invoiceUrl) . "</a><br><br>";

    // 3) Send email with invoice attachment
    echo "<b>Sending email to:</b> " . htmlspecialchars($booking['email']) . "<br>";

    $sent = sendBookingConfirmationEmail($booking, $invoicePath);

    echo "<br><b>Email sent?</b> ";
    var_dump($sent);

    if ($sent !== true) {
        echo "<br><br><b style='color:red;'>Email FAILED.</b><br>";
        echo "Open XAMPP error log to see the reason:<br>";
        echo "<pre>C:\\xampp\\php\\logs\\php_error_log</pre>";
        echo "<pre>or Apache log: C:\\xampp\\apache\\logs\\error.log</pre>";
    } else {
        echo "<br><br><b style='color:green;'>✅ Email sent successfully (with invoice attachment).</b>";
    }

} catch (Throwable $e) {
    echo "<h3 style='color:red;'>ERROR</h3>";
    echo "<pre style='color:red;'>" . htmlspecialchars($e->getMessage()) . "</pre>";
}
