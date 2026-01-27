<?php
/**
 * JUnit Testing Concept Implementation - PHPUnit Equivalent
 * 
 * This class is similar to MessageUtil in JUnit example.
 * In JUnit, we create a class to be tested. Here, FormValidator is that class.
 * 
 * This class provides form validation utilities for the tours and hospitality website.
 * It validates user inputs like names, emails, phone numbers, etc.
 */

class FormValidator {
    
    private $message;
    
    /**
     * Constructor
     * @param string $message - Message to be validated/processed
     */
    public function __construct($message = '') {
        $this->message = $message;
    }
    
    /**
     * Prints the message on console (similar to printMessage in JUnit example)
     * @return string - Returns the message
     */
    public function printMessage() {
        echo $this->message . "\n";
        return $this->message;
    }
    
    /**
     * Validates full name - only letters and spaces allowed
     * @param string $name - Name to validate
     * @return bool - True if valid, false otherwise
     */
    public function validateFullName($name) {
        if (empty(trim($name))) {
            return false;
        }
        return preg_match("/^[a-zA-Z ]+$/", trim($name)) === 1;
    }
    
    /**
     * Validates email format
     * @param string $email - Email to validate
     * @return bool - True if valid email format, false otherwise
     */
    public function validateEmail($email) {
        if (empty(trim($email))) {
            return false;
        }
        return filter_var(trim($email), FILTER_VALIDATE_EMAIL) !== false;
    }
    
    /**
     * Validates phone number - only digits allowed
     * @param string $phone - Phone number to validate
     * @return bool - True if valid, false otherwise
     */
    public function validatePhone($phone) {
        if (empty(trim($phone))) {
            return false;
        }
        return preg_match("/^[0-9]+$/", trim($phone)) === 1;
    }
    
    /**
     * Validates date range - checks if from_date is before to_date
     * @param string $fromDate - Start date
     * @param string $toDate - End date
     * @return bool - True if valid date range, false otherwise
     */
    public function validateDateRange($fromDate, $toDate) {
        if (empty($fromDate) || empty($toDate)) {
            return false;
        }
        return strtotime($fromDate) <= strtotime($toDate);
    }
    
    /**
     * Validates number of guests - must be greater than 0
     * @param int|string $guests - Number of guests
     * @return bool - True if valid, false otherwise
     */
    public function validateGuests($guests) {
        $guests = (int)$guests;
        return $guests > 0;
    }
}
