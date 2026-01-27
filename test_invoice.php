<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once __DIR__ . "/includes/invoice.php";

// Fake booking data (same keys as your system)
$booking = [
  "product_name" => "Test Glamp - Luxury Tent",
  "full_name"    => "Invoice Test User",
  "email"        => "testuser@gmail.com",
  "phone"        => "03001234567",
  "from_date"    => "2026-02-01",
  "to_date"      => "2026-02-05",
  "guests"       => "2",
];

// any dummy bookingId for filename
$bookingId = 999;

echo "Generating invoice for Booking ID: {$bookingId}<br><br>";

$invoicePath = generateInvoice($booking, $bookingId);

echo "Invoice Path: " . htmlspecialchars($invoicePath) . "<br>";
echo "File exists? ";
var_dump(file_exists($invoicePath));
echo "<br><br>";

// show link for browser open
$invoiceUrl = "http://localhost/scd/invoices/invoice_" . $bookingId . ".html";
echo "Open Invoice in Browser: <a href='{$invoiceUrl}' target='_blank'>{$invoiceUrl}</a>";
