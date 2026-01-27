<?php
/**
 * JUnit Testing Concept Implementation - PHPUnit Equivalent
 * 
 * This is the Test Runner Class (similar to TestRunner.java in JUnit example)
 * 
 * In JUnit:
 * - JUnitCore class is used to run tests
 * - JUnitCore.runClasses(TestClass.class) executes test cases
 * - Result object contains test execution results
 * - result.wasSuccessful() returns true if all tests pass
 * 
 * In PHPUnit:
 * - PHPUnit\TextUI\Command::main() or vendor/bin/phpunit is used
 * - We can also use command line: vendor/bin/phpunit TestFormValidator.php
 * - This file demonstrates programmatic test execution (similar to JUnit example)
 * 
 * To run: php tests/TestRunner.php
 * OR: vendor/bin/phpunit tests/TestFormValidator.php
 */

require_once __DIR__ . '/TestFormValidator.php';
require_once __DIR__ . '/../vendor/autoload.php';

use PHPUnit\Framework\TestSuite;
use PHPUnit\TextUI\Command;

/**
 * TestRunner class - Similar to TestRunner in JUnit example
 * 
 * In JUnit:
 * public static void main(String[] args) {
 *     Result result = JUnitCore.runClasses(TestJunit.class);
 *     for (Failure failure : result.getFailures()) {
 *         System.out.println(failure.toString());
 *     }
 *     System.out.println(result.wasSuccessful());
 * }
 * 
 * In PHPUnit, we use Command::main() which is equivalent to JUnitCore.runClasses()
 */
class TestRunnerMain {
    
    public static function main($args = []) {
        echo "=== JUnit Testing Concept - PHPUnit Implementation ===\n";
        echo "Running tests for FormValidator class...\n";
        echo "This is equivalent to: JUnitCore.runClasses(TestJunit.class)\n\n";
        
        // In JUnit: Result result = JUnitCore.runClasses(TestJunit.class);
        // In PHPUnit: We use Command::main() which internally uses JUnitCore equivalent
        // The command line arguments tell PHPUnit which test class to run
        
        $testFile = __DIR__ . '/TestFormValidator.php';
        
        // Build command line arguments for PHPUnit
        // Equivalent to: java org.junit.runner.JUnitCore TestJunit
        $phpunitArgs = [
            'phpunit',                    // Command name
            $testFile,                    // Test class file (equivalent to TestJunit.class)
            '--verbose',                  // Show detailed output
            '--colors=always'             // Colored output
        ];
        
        // Execute PHPUnit (equivalent to JUnitCore.runClasses())
        // This will run all tests in TestFormValidator class
        exit(Command::main($phpunitArgs));
    }
}

// Execute tests if run directly from command line
// Similar to: java TestRunner in JUnit example
// Usage: php tests/TestRunner.php
if (php_sapi_name() === 'cli' && basename(__FILE__) === basename($_SERVER['SCRIPT_NAME'])) {
    TestRunnerMain::main($argv);
}
