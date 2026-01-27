<?php
/**
 * JUnit Testing Concept Implementation - PHPUnit Equivalent
 * 
 * This test class demonstrates testing the BookingFacade class
 * which is part of the existing project functionality.
 * 
 * This shows how JUnit testing concepts apply to real project code.
 */

require_once __DIR__ . '/../facades/BookingFacade.php';
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../vendor/autoload.php';

use PHPUnit\Framework\TestCase;

class TestBookingFacade extends TestCase {
    
    private $conn;
    private $bookingFacade;
    
    /**
     * setUp() method - Similar to @Before in JUnit
     * This runs before each test method
     * In JUnit: @Before annotation
     * In PHPUnit: setUp() method
     */
    protected function setUp(): void {
        // Get database connection (using existing Database singleton)
        $db = Database::getInstance();
        $this->conn = $db->getConnection();
        $this->bookingFacade = new BookingFacade($this->conn);
    }
    
    /**
     * Test method for createBooking() - Valid booking data
     * @test - Equivalent to JUnit's @Test annotation
     */
    public function testCreateBookingValid() {
        // Arrange: Prepare valid booking data
        $validData = [
            'product_name' => 'Luxury Glamping Tent',
            'full_name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '1234567890',
            'from_date' => '2025-02-01',
            'to_date' => '2025-02-05',
            'guests' => '2'
        ];
        
        // Act: Execute the method
        // Note: This will actually insert into database
        // In a real scenario, you might use a test database or mock
        $result = $this->bookingFacade->createBooking($validData);
        
        // Assert: Check if booking was created successfully
        // assertEquals(expected, actual) - same as JUnit
        $this->assertEquals(true, $result);
    }
    
    /**
     * Test method for createBooking() - Invalid name (contains numbers)
     * This tests validation logic
     */
    public function testCreateBookingInvalidName() {
        // Arrange: Prepare invalid booking data (name with numbers)
        $invalidData = [
            'product_name' => 'Luxury Glamping Tent',
            'full_name' => 'John123', // Invalid: contains numbers
            'email' => 'john@example.com',
            'phone' => '1234567890',
            'from_date' => '2025-02-01',
            'to_date' => '2025-02-05',
            'guests' => '2'
        ];
        
        // Act & Assert: Expect Exception to be thrown
        // In JUnit: @Test(expected = Exception.class)
        // In PHPUnit: expectException()
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Name invalid");
        
        $this->bookingFacade->createBooking($invalidData);
    }
    
    /**
     * Test method for createBooking() - Invalid email format
     */
    public function testCreateBookingInvalidEmail() {
        $invalidData = [
            'product_name' => 'Luxury Glamping Tent',
            'full_name' => 'John Doe',
            'email' => 'invalid-email', // Invalid email format
            'phone' => '1234567890',
            'from_date' => '2025-02-01',
            'to_date' => '2025-02-05',
            'guests' => '2'
        ];
        
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Invalid email");
        
        $this->bookingFacade->createBooking($invalidData);
    }
    
    /**
     * Test method for createBooking() - Invalid phone (contains non-digits)
     */
    public function testCreateBookingInvalidPhone() {
        $invalidData = [
            'product_name' => 'Luxury Glamping Tent',
            'full_name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '123-456-7890', // Invalid: contains dashes
            'from_date' => '2025-02-01',
            'to_date' => '2025-02-05',
            'guests' => '2'
        ];
        
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Phone must be digits");
        
        $this->bookingFacade->createBooking($invalidData);
    }
    
    /**
     * Test method for createBooking() - Invalid date range (to_date before from_date)
     */
    public function testCreateBookingInvalidDateRange() {
        $invalidData = [
            'product_name' => 'Luxury Glamping Tent',
            'full_name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '1234567890',
            'from_date' => '2025-02-05',
            'to_date' => '2025-02-01', // Invalid: to_date before from_date
            'guests' => '2'
        ];
        
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Invalid date range");
        
        $this->bookingFacade->createBooking($invalidData);
    }
    
    /**
     * Test method for createBooking() - Invalid guests (zero or negative)
     */
    public function testCreateBookingInvalidGuests() {
        $invalidData = [
            'product_name' => 'Luxury Glamping Tent',
            'full_name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '1234567890',
            'from_date' => '2025-02-01',
            'to_date' => '2025-02-05',
            'guests' => '0' // Invalid: must be > 0
        ];
        
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Guests must be > 0");
        
        $this->bookingFacade->createBooking($invalidData);
    }
    
    /**
     * Test method for createBooking() - Missing product name
     */
    public function testCreateBookingMissingProductName() {
        $invalidData = [
            'product_name' => '', // Missing product name
            'full_name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '1234567890',
            'from_date' => '2025-02-01',
            'to_date' => '2025-02-05',
            'guests' => '2'
        ];
        
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Product name missing");
        
        $this->bookingFacade->createBooking($invalidData);
    }
}
