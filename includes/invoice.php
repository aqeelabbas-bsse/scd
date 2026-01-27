<?php

function generateInvoice(array $booking, int $bookingId): string
{
    $dir = __DIR__ . '/../invoices';
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }

    $file = $dir . "/invoice_" . $bookingId . ".html";

    $today = date("Y-m-d H:i:s");

    $html = "
<!doctype html>
<html>
<head>
  <meta charset='utf-8'>
  <title>Invoice #{$bookingId}</title>
  <style>
    body{font-family:Arial, sans-serif; padding:20px;}
    .card{border:1px solid #ddd; border-radius:10px; padding:18px; max-width:720px;}
    h2{margin:0 0 10px 0;}
    table{width:100%; border-collapse:collapse; margin-top:10px;}
    td{padding:8px; border-bottom:1px solid #eee;}
    .right{text-align:right;}
    .muted{color:#666; font-size:13px;}
  </style>
</head>
<body>
  <div class='card'>
    <h2>Invoice</h2>
    <div class='muted'>Generated: {$today}</div>
    <div class='muted'>Booking ID: {$bookingId}</div>

    <table>
      <tr><td><b>Name</b></td><td class='right'>{$booking['full_name']}</td></tr>
      <tr><td><b>Email</b></td><td class='right'>{$booking['email']}</td></tr>
      <tr><td><b>Phone</b></td><td class='right'>{$booking['phone']}</td></tr>
      <tr><td><b>Product</b></td><td class='right'>{$booking['product_name']}</td></tr>
      <tr><td><b>From</b></td><td class='right'>{$booking['from_date']}</td></tr>
      <tr><td><b>To</b></td><td class='right'>{$booking['to_date']}</td></tr>
      <tr><td><b>Guests</b></td><td class='right'>{$booking['guests']}</td></tr>
    </table>

    <p class='muted'>Thank you for booking with Wanderlust Tours.</p>
  </div>
</body>
</html>";

    file_put_contents($file, $html);

    return $file;
}
