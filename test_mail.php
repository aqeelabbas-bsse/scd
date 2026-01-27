<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

$path = __DIR__ . "/includes/mailer.php";

echo "Trying to load: " . $path . "<br>";
echo "File exists? " . (file_exists($path) ? "YES" : "NO") . "<br>";

if (!file_exists($path)) {
    die("❌ mailer.php not found at: " . $path);
}

require $path;

echo "Function exists now? ";
var_dump(function_exists('sendBookingConfirmationEmail'));
echo "<br>";

// stop here if function still not found
if (!function_exists('sendBookingConfirmationEmail')) {
    die("❌ Function not defined. That means mailer.php has an error or the function name is different.");
}

// test call
$booking = [
  "full_name" => "Test User 2",
  "email" => "YOUR_RECEIVER_EMAIL@gmail.com",
  "phone" => "123",
  "from_date" => "2026-01-15",
  "to_date" => "2026-01-23",
  "guests" => "2",
  "product_name" => "Test Glamp"
];

$result = sendBookingConfirmationEmail($booking);
echo $result ? "EMAIL SENT ✅" : "EMAIL FAILED ❌";
