<?php
/**
 * Contact Us Form Validator
 * Matches the exact validation rules used in /contact-us/index.php
 */
class ContactValidator
{
    // Full Name: letters + spaces only (non-empty)
    public static function validFullName(string $name): bool
    {
        $name = trim($name);
        if ($name === '') return false;
        return preg_match('/^[A-Za-z ]+$/', $name) === 1;
    }

    // Email: standard email format
    public static function validEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    // Phone: digits only (non-empty)
    public static function validPhone(string $phone): bool
    {
        $phone = trim($phone);
        if ($phone === '') return false;
        return preg_match('/^[0-9]+$/', $phone) === 1;
    }

    // Travel place: free text in your form (allow empty as per current code)
    public static function validTravelPlace(string $travelPlace): bool
    {
        // Your current code does NO validation, so always return true
        return true;
    }

    // Experience type: must not be empty
    public static function validExperienceType(string $experienceType): bool
    {
        return trim($experienceType) !== '';
    }
}
