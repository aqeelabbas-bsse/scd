<?php

class BookingValidator
{
    // Names: alphabets + spaces only
    public static function validFullName(string $name): bool
    {
        $name = trim($name);
        return $name !== '' && preg_match('/^[A-Za-z ]+$/', $name) === 1;
    }

    // Email format
    public static function validEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    // Phone: allow +, digits, spaces, hyphen (min 10 digits)
    public static function validPhone(string $phone): bool
    {
        $phone = trim($phone);
        if ($phone === '') return false;

        // keep digits only to check length
        $digits = preg_replace('/\D/', '', $phone);
        if (strlen($digits) < 10) return false;

        return preg_match('/^[0-9+\-\s()]+$/', $phone) === 1;
    }

    // Date format YYYY-MM-DD and start <= end
    public static function validDateRange(string $start, string $end): bool
    {
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $start)) return false;
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $end)) return false;

        $s = strtotime($start);
        $e = strtotime($end);

        if ($s === false || $e === false) return false;
        return $s <= $e;
    }

    // Guests: 1 to 20 (adjust if you want)
    public static function validGuests($guests): bool
    {
        if (!is_numeric($guests)) return false;
        $g = (int)$guests;
        return $g >= 1 && $g <= 20;
    }
}
