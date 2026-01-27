<?php
/**
 * Home Page (index.php) Auth/Form Validator
 * Matches the helper rules inside /index.php:
 * - Email format
 * - Strong password (1 upper, 1 lower, 1 digit, 1 special, min 8)
 * - Username/full_name must contain at least 1 letter and 1 digit (spaces allowed)
 */
class HomePageValidator
{
    public static function validEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    public static function strongPassword(string $pass): bool
    {
        return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,}$/', $pass) === 1;
    }

    // Must contain letters + digits. Allow spaces. Only letters, digits, spaces are allowed.
    public static function validUsernameFullName(string $name): bool
    {
        $name = trim($name);
        if ($name === '') return false;
        return preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d ]+$/', $name) === 1;
    }

    public static function passwordsMatch(string $p1, string $p2): bool
    {
        return $p1 === $p2;
    }
}
