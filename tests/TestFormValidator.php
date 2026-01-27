<?php
/**
 * JUnit Testing Concept Implementation - PHPUnit Equivalent
 * 
 * This is the Test Case Class (similar to TestJunit.java in JUnit example)
 * 
 * In JUnit:
 * - We use @Test annotation to mark test methods
 * - We use assertEquals() to check conditions
 * 
 * In PHPUnit:
 * - We use @test annotation or method names starting with "test"
 * - We use assertEquals() from PHPUnit\Framework\TestCase
 * 
 * This class tests the FormValidator class functionality.
 */

require_once __DIR__ . '/utils/FormValidator.php';
require_once __DIR__ . '/../vendor/autoload.php';

use PHPUnit\Framework\TestCase;

class TestFormValidator extends TestCase {
    
    /**
     * Test method for printMessage() - Similar to testPrintMessage() in JUnit example
     * 
     * In JUnit: @Test annotation marks this as a test method
     * In PHPUnit: Method name starting with "test" or @test annotation
     * 
     * This test verifies that printMessage() returns the correct message
     */
    public function testPrintMessage() {
        // Arrange: Set up test data (similar to JUnit example)
        $message = "Hello World";
        $formValidator = new FormValidator($message);
        
        // Act: Execute the method to test
        $result = $formValidator->printMessage();
        
        // Assert: Check if result matches expected value
        // In JUnit: assertEquals(message, messageUtil.printMessage())
        // In PHPUnit: $this->assertEquals(expected, actual)
        $this->assertEquals($message, $result);
    }
    
    /**
     * Test method for validateFullName() - Valid name test
     * @test annotation equivalent to JUnit's @Test
     */
    public function testValidateFullNameValid() {
        $formValidator = new FormValidator();
        
        // Test with valid name (only letters and spaces)
        $validName = "John Doe";
        $result = $formValidator->validateFullName($validName);
        
        // assertEquals(expected, actual) - same as JUnit
        $this->assertEquals(true, $result);
    }
    
    /**
     * Test method for validateFullName() - Invalid name test
     */
    public function testValidateFullNameInvalid() {
        $formValidator = new FormValidator();
        
        // Test with invalid name (contains numbers)
        $invalidName = "John123";
        $result = $formValidator->validateFullName($invalidName);
        
        $this->assertEquals(false, $result);
    }
    
    /**
     * Test method for validateEmail() - Valid email test
     */
    public function testValidateEmailValid() {
        $formValidator = new FormValidator();
        
        $validEmail = "test@example.com";
        $result = $formValidator->validateEmail($validEmail);
        
        $this->assertEquals(true, $result);
    }
    
    /**
     * Test method for validateEmail() - Invalid email test
     */
    public function testValidateEmailInvalid() {
        $formValidator = new FormValidator();
        
        $invalidEmail = "invalid-email";
        $result = $formValidator->validateEmail($invalidEmail);
        
        $this->assertEquals(false, $result);
    }
    
    /**
     * Test method for validatePhone() - Valid phone test
     */
    public function testValidatePhoneValid() {
        $formValidator = new FormValidator();
        
        $validPhone = "1234567890";
        $result = $formValidator->validatePhone($validPhone);
        
        $this->assertEquals(true, $result);
    }
    
    /**
     * Test method for validatePhone() - Invalid phone test
     */
    public function testValidatePhoneInvalid() {
        $formValidator = new FormValidator();
        
        $invalidPhone = "123-456-7890"; // Contains dashes
        $result = $formValidator->validatePhone($invalidPhone);
        
        $this->assertEquals(false, $result);
    }
    
    /**
     * Test method for validateDateRange() - Valid date range test
     */
    public function testValidateDateRangeValid() {
        $formValidator = new FormValidator();
        
        $fromDate = "2025-01-01";
        $toDate = "2025-01-10";
        $result = $formValidator->validateDateRange($fromDate, $toDate);
        
        $this->assertEquals(true, $result);
    }
    
    /**
     * Test method for validateDateRange() - Invalid date range test
     */
    public function testValidateDateRangeInvalid() {
        $formValidator = new FormValidator();
        
        $fromDate = "2025-01-10";
        $toDate = "2025-01-01"; // To date before from date
        $result = $formValidator->validateDateRange($fromDate, $toDate);
        
        $this->assertEquals(false, $result);
    }
    
    /**
     * Test method for validateGuests() - Valid guests test
     */
    public function testValidateGuestsValid() {
        $formValidator = new FormValidator();
        
        $guests = 5;
        $result = $formValidator->validateGuests($guests);
        
        $this->assertEquals(true, $result);
    }
    
    /**
     * Test method for validateGuests() - Invalid guests test
     */
    public function testValidateGuestsInvalid() {
        $formValidator = new FormValidator();
        
        $guests = 0; // Must be greater than 0
        $result = $formValidator->validateGuests($guests);
        
        $this->assertEquals(false, $result);
    }
}
