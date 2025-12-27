<?php

class BookingFacade {

    private $conn;

    public function __construct($connection) {
        $this->conn = $connection;
    }

    public function createBooking($data) {

        $product_name = trim($data['product_name']);
        $full_name    = trim($data['full_name']);
        $email        = trim($data['email']);
        $phone        = trim($data['phone']);
        $from_date    = trim($data['from_date']);
        $to_date      = trim($data['to_date']);
        $guests       = trim($data['guests']);

        // VALIDATIONS (same as before)
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

        // Database Insert
        $stmt = $this->conn->prepare("
            INSERT INTO bookings 
                (product_name, full_name, email, phone, from_date, to_date, guests)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
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
        $stmt->execute();
        $stmt->close();

        return true;
    }
}