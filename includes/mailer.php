<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php';

/**
 * Sends booking confirmation email
 * If invoice path is provided, it attaches invoice automatically
 */
function sendBookingConfirmationEmail(array $booking, ?string $invoicePath = null): bool {

    $mail = new PHPMailer(true);

    try {
        // SMTP config
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'camer4090@gmail.com';
        $mail->Password   = 'xwevkhaxrietmxqw'; // app password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Sender + Receiver
        $mail->setFrom('camer4090@gmail.com', 'Wanderlust Tours');
        $mail->addAddress($booking['email'], $booking['full_name']);

        // (optional) admin notification
        $mail->addCC('camer4090@gmail.com');

        // Email content
        $mail->isHTML(true);
        $mail->Subject = 'Booking Confirmation';

        $mail->Body = "
            <h2>Booking Confirmed ✅</h2>
            <p><b>Name:</b> {$booking['full_name']}</p>
            <p><b>Product:</b> {$booking['product_name']}</p>
            <p><b>From:</b> {$booking['from_date']}</p>
            <p><b>To:</b> {$booking['to_date']}</p>
            <p><b>Guests:</b> {$booking['guests']}</p>
            <p>Thank you for booking with <b>Wanderlust Tours</b>.</p>
        ";

        // ✅ Attach invoice if exists
        if ($invoicePath && file_exists($invoicePath)) {
            $mail->addAttachment($invoicePath, 'Invoice.html');
        }

        return $mail->send();

    } catch (Exception $e) {
        // for debugging (optional)
        // echo $mail->ErrorInfo;
        return false;
    }
}
