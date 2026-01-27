<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . "/HomePageValidator.php";

class TestHomePageValidator extends TestCase
{
    public function testValidEmail()
    {
        $this->assertTrue(HomePageValidator::validEmail("test@gmail.com"));
        $this->assertFalse(HomePageValidator::validEmail("bad-email"));
    }

    public function testStrongPassword()
    {
        $this->assertTrue(HomePageValidator::strongPassword("Abc@1234"));
        $this->assertFalse(HomePageValidator::strongPassword("abc12345"));  // no uppercase + special
        $this->assertFalse(HomePageValidator::strongPassword("ABC@1234"));  // no lowercase
        $this->assertFalse(HomePageValidator::strongPassword("Abcdefgh@")); // no digit
        $this->assertFalse(HomePageValidator::strongPassword("Abc12345"));  // no special
        $this->assertFalse(HomePageValidator::strongPassword("A@1a"));      // too short
    }

    public function testUsernameFullNameValid()
    {
        // Must contain both letters and digits
        $this->assertTrue(HomePageValidator::validUsernameFullName("Ali Malik 12"));
        $this->assertTrue(HomePageValidator::validUsernameFullName("User1"));
        $this->assertTrue(HomePageValidator::validUsernameFullName("A 1"));
    }

    public function testUsernameFullNameInvalid()
    {
        $this->assertFalse(HomePageValidator::validUsernameFullName(""));          // empty
        $this->assertFalse(HomePageValidator::validUsernameFullName("Ali Malik")); // no digit
        $this->assertFalse(HomePageValidator::validUsernameFullName("12345"));     // no letter
        $this->assertFalse(HomePageValidator::validUsernameFullName("Ali@12"));    // invalid char @
    }

    public function testPasswordsMatch()
    {
        $this->assertTrue(HomePageValidator::passwordsMatch("Abc@1234", "Abc@1234"));
        $this->assertFalse(HomePageValidator::passwordsMatch("Abc@1234", "Abc@9999"));
    }
}
