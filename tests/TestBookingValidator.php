<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . "/BookingValidator.php";

class TestBookingValidator extends TestCase
{
    public function testValidFullNameValid()
    {
        $this->assertTrue(BookingValidator::validFullName("Ali Malik"));
        $this->assertTrue(BookingValidator::validFullName("Farhan Khan"));
    }

    public function testValidFullNameInvalid()
    {
        $this->assertFalse(BookingValidator::validFullName(""));
        $this->assertFalse(BookingValidator::validFullName("Ali123"));
        $this->assertFalse(BookingValidator::validFullName("Ali@Malik"));
    }

    public function testValidateEmailValid()
    {
        $this->assertTrue(BookingValidator::validEmail("test@gmail.com"));
        $this->assertTrue(BookingValidator::validEmail("a.b+1@domain.com"));
    }

    public function testValidateEmailInvalid()
    {
        $this->assertFalse(BookingValidator::validEmail("bad-email"));
        $this->assertFalse(BookingValidator::validEmail("test@"));
    }

    public function testValidatePhoneValid()
    {
        $this->assertTrue(BookingValidator::validPhone("+92 301 1234567"));
        $this->assertTrue(BookingValidator::validPhone("0301-1234567"));
        $this->assertTrue(BookingValidator::validPhone("(0301) 1234567"));
    }

    public function testValidatePhoneInvalid()
    {
        $this->assertFalse(BookingValidator::validPhone("123"));          // too short
        $this->assertFalse(BookingValidator::validPhone("phone123456"));  // letters
        $this->assertFalse(BookingValidator::validPhone(""));             // empty
    }

    public function testValidateDateRangeValid()
    {
        $this->assertTrue(BookingValidator::validDateRange("2026-01-01", "2026-01-05"));
        $this->assertTrue(BookingValidator::validDateRange("2026-01-05", "2026-01-05")); // same day ok
    }

    public function testValidateDateRangeInvalid()
    {
        $this->assertFalse(BookingValidator::validDateRange("2026-01-10", "2026-01-05")); // start > end
        $this->assertFalse(BookingValidator::validDateRange("01-01-2026", "2026-01-05")); // bad format
        $this->assertFalse(BookingValidator::validDateRange("2026-01-01", "05-01-2026")); // bad format
    }

    public function testValidateGuestsValid()
    {
        $this->assertTrue(BookingValidator::validGuests(1));
        $this->assertTrue(BookingValidator::validGuests(5));
        $this->assertTrue(BookingValidator::validGuests("10"));
    }

    public function testValidateGuestsInvalid()
    {
        $this->assertFalse(BookingValidator::validGuests(0));
        $this->assertFalse(BookingValidator::validGuests(21));
        $this->assertFalse(BookingValidator::validGuests("abc"));
    }
}
