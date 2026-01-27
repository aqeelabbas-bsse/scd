<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . "/ContactValidator.php";

class TestContactValidator extends TestCase
{
    public function testValidFullNameValid()
    {
        $this->assertTrue(ContactValidator::validFullName("Ali Malik"));
        $this->assertTrue(ContactValidator::validFullName("John Doe"));
    }

    public function testValidFullNameInvalid()
    {
        $this->assertFalse(ContactValidator::validFullName(""));
        $this->assertFalse(ContactValidator::validFullName("Ali123"));
        $this->assertFalse(ContactValidator::validFullName("Ali@Malik"));
    }

    public function testValidEmailValid()
    {
        $this->assertTrue(ContactValidator::validEmail("test@gmail.com"));
        $this->assertTrue(ContactValidator::validEmail("a.b+1@domain.com"));
    }

    public function testValidEmailInvalid()
    {
        $this->assertFalse(ContactValidator::validEmail("bad-email"));
        $this->assertFalse(ContactValidator::validEmail("test@"));
    }

    public function testValidPhoneValid()
    {
        // Contact form rule: digits ONLY
        $this->assertTrue(ContactValidator::validPhone("03011234567"));
        $this->assertTrue(ContactValidator::validPhone("1234567890"));
    }

    public function testValidPhoneInvalid()
    {
        $this->assertFalse(ContactValidator::validPhone(""));                // empty
        $this->assertFalse(ContactValidator::validPhone("+92 301 1234567")); // plus + spaces not allowed
        $this->assertFalse(ContactValidator::validPhone("0301-1234567"));    // hyphen not allowed
        $this->assertFalse(ContactValidator::validPhone("phone123"));        // letters not allowed
    }

    public function testExperienceTypeValid()
    {
        $this->assertTrue(ContactValidator::validExperienceType("Adventure"));
        $this->assertTrue(ContactValidator::validExperienceType("Family Trip"));
    }

    public function testExperienceTypeInvalid()
    {
        $this->assertFalse(ContactValidator::validExperienceType(""));
        $this->assertFalse(ContactValidator::validExperienceType("   "));
    }

    public function testTravelPlaceAlwaysValid()
    {
        // Your current form does NO validation on travel_place
        $this->assertTrue(ContactValidator::validTravelPlace(""));
        $this->assertTrue(ContactValidator::validTravelPlace("Swat Valley"));
        $this->assertTrue(ContactValidator::validTravelPlace("Any random text 123 !@#"));
    }
}
