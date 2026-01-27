<?php
/**
 * BookingFacade (SCD Final - Async / Multithreading Concept)
 * - Validates booking data
 * - Inserts booking into DB
 * - Enqueues background job for invoice + email (instead of doing it synchronously)
 */

class BookingFacade {

    private mysqli $conn;

    public function __construct(mysqli $connection) {
        $this->conn = $connection;
    }

    public function createBooking(array $data): bool {

        // 1) Read + sanitize
        $product_name = trim($data['product_name'] ?? '');
        $full_name    = trim($data['full_name'] ?? '');
        $email        = trim($data['email'] ?? '');
        $phone        = trim($data['phone'] ?? '');
        $from_date    = trim($data['from_date'] ?? '');
        $to_date      = trim($data['to_date'] ?? '');
        $guests       = trim($data['guests'] ?? '');

        // 2) Validations
        if ($product_name === '') {
            throw new Exception("Product name missing");
        }
        if (!preg_match("/^[a-zA-Z ]+$/", $full_name)) {
            throw new Exception("Name invalid");
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email");
        }
        if (!preg_match("/^[0-9]+$/", $phone)) {
            throw new Exception("Phone must be digits");
        }
        if ($from_date === '' || $to_date === '') {
            throw new Exception("Dates required");
        }
        if ($from_date > $to_date) {
            throw new Exception("Invalid date range");
        }
        if ($guests === '' || (int)$guests < 1) {
            throw new Exception("Guests must be > 0");
        }

        // 3) Insert into DB
        $stmt = $this->conn->prepare("
            INSERT INTO bookings 
                (product_name, full_name, email, phone, from_date, to_date, guests)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $this->conn->error);
        }

        $stmt->bind_param(
            "sssssss",
            $product_name,
            $full_name,
            $email,
            $phone,
            $from_date,
            $to_date,
            $guests
        );

        $ok = $stmt->execute();
        if (!$ok) {
            $err = $stmt->error;
            $stmt->close();
            throw new Exception("Insert failed: " . $err);
        }

        $bookingId = (int)$this->conn->insert_id;
        $stmt->close();

        // 4) Build booking payload for background processing
        $booking = [
            "booking_id"   => $bookingId,
            "product_name" => $product_name,
            "full_name"    => $full_name,
            "email"        => $email,
            "phone"        => $phone,
            "from_date"    => $from_date,
            "to_date"      => $to_date,
            "guests"       => $guests,
        ];

        // 5) Enqueue background job (multithreading concept)
        $payload = json_encode($booking, JSON_UNESCAPED_UNICODE);

        $jobStmt = $this->conn->prepare("INSERT INTO jobs (type, payload, status) VALUES (?, ?, 'pending')");
        if (!$jobStmt) {
            // Booking is saved, but job enqueue failed
            // You can throw exception OR just return true (up to you). For demo, throw:
            throw new Exception("Job enqueue prepare failed: " . $this->conn->error);
        }

        $type = "SEND_EMAIL_AND_INVOICE";
        $jobStmt->bind_param("ss", $type, $payload);

        $jobOk = $jobStmt->execute();
        if (!$jobOk) {
            $err = $jobStmt->error;
            $jobStmt->close();
            throw new Exception("Job enqueue failed: " . $err);
        }
        $jobStmt->close();

        // ✅ Return immediately (fast response) — email+invoice will happen in worker
        return true;
    }
}
