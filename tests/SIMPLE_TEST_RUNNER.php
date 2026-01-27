<?php
/**
 * Simple Test Runner - Bina Composer ke bhi kaam karega
 * 
 * Ye file JUnit example ke TestRunner.java jaisa hai
 * Agar PHPUnit install nahi hai, to ye simple tests run karega
 */

echo "=== JUnit Testing Concept - Simple Test Runner ===\n";
echo "Running FormValidator tests...\n\n";

// FormValidator class load karein
require_once __DIR__ . '/utils/FormValidator.php';

// Test results track karein
$testsRun = 0;
$testsPassed = 0;
$testsFailed = 0;
$failures = [];

/**
 * Simple assertEquals function (JUnit jaisa)
 */
function assertEquals($expected, $actual, $testName = '') {
    global $testsRun, $testsPassed, $testsFailed, $failures;
    
    $testsRun++;
    
    if ($expected === $actual) {
        $testsPassed++;
        echo "✓ PASS: $testName\n";
        return true;
    } else {
        $testsFailed++;
        $failures[] = [
            'test' => $testName,
            'expected' => $expected,
            'actual' => $actual
        ];
        echo "✗ FAIL: $testName\n";
        echo "  Expected: " . var_export($expected, true) . "\n";
        echo "  Actual: " . var_export($actual, true) . "\n";
        return false;
    }
}

// ==================== TESTS START ====================

echo "--- Test 1: testPrintMessage() ---\n";
$message = "Hello World";
$formValidator = new FormValidator($message);
$result = $formValidator->printMessage();
assertEquals($message, $result, "testPrintMessage");

echo "\n--- Test 2: testValidateFullNameValid() ---\n";
$formValidator = new FormValidator();
$validName = "John Doe";
$result = $formValidator->validateFullName($validName);
assertEquals(true, $result, "testValidateFullNameValid");

echo "\n--- Test 3: testValidateFullNameInvalid() ---\n";
$invalidName = "John123";
$result = $formValidator->validateFullName($invalidName);
assertEquals(false, $result, "testValidateFullNameInvalid");

echo "\n--- Test 4: testValidateEmailValid() ---\n";
$validEmail = "test@example.com";
$result = $formValidator->validateEmail($validEmail);
assertEquals(true, $result, "testValidateEmailValid");

echo "\n--- Test 5: testValidateEmailInvalid() ---\n";
$invalidEmail = "invalid-email";
$result = $formValidator->validateEmail($invalidEmail);
assertEquals(false, $result, "testValidateEmailInvalid");

echo "\n--- Test 6: testValidatePhoneValid() ---\n";
$validPhone = "1234567890";
$result = $formValidator->validatePhone($validPhone);
assertEquals(true, $result, "testValidatePhoneValid");

echo "\n--- Test 7: testValidatePhoneInvalid() ---\n";
$invalidPhone = "123-456-7890";
$result = $formValidator->validatePhone($invalidPhone);
assertEquals(false, $result, "testValidatePhoneInvalid");

echo "\n--- Test 8: testValidateDateRangeValid() ---\n";
$fromDate = "2025-01-01";
$toDate = "2025-01-10";
$result = $formValidator->validateDateRange($fromDate, $toDate);
assertEquals(true, $result, "testValidateDateRangeValid");

echo "\n--- Test 9: testValidateDateRangeInvalid() ---\n";
$fromDate = "2025-01-10";
$toDate = "2025-01-01";
$result = $formValidator->validateDateRange($fromDate, $toDate);
assertEquals(false, $result, "testValidateDateRangeInvalid");

echo "\n--- Test 10: testValidateGuestsValid() ---\n";
$guests = 5;
$result = $formValidator->validateGuests($guests);
assertEquals(true, $result, "testValidateGuestsValid");

echo "\n--- Test 11: testValidateGuestsInvalid() ---\n";
$guests = 0;
$result = $formValidator->validateGuests($guests);
assertEquals(false, $result, "testValidateGuestsInvalid");

// ==================== TESTS END ====================

echo "\n";
echo "========================================\n";
echo "=== Test Results ===\n";
echo "========================================\n";
echo "Tests run: $testsRun\n";
echo "Passed: $testsPassed\n";
echo "Failed: $testsFailed\n";
echo "\n";

if ($testsFailed === 0) {
    echo "Result: true\n";
    echo "✓ All tests passed successfully!\n";
} else {
    echo "Result: false\n";
    echo "✗ Some tests failed:\n";
    foreach ($failures as $failure) {
        echo "  - " . $failure['test'] . "\n";
    }
}

echo "\n";
echo "========================================\n";
echo "This is similar to JUnit's TestRunner output\n";
echo "For full PHPUnit features, run: composer install\n";
echo "========================================\n";
