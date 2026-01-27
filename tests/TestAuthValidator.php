<?php
use PHPUnit\Framework\TestCase;
require_once __DIR__ . "/AuthValidator.php";

class TestAuthValidator extends TestCase {

    public function testValidEmail() {
        $this->assertTrue(AuthValidator::validEmail("test@gmail.com"));
        $this->assertFalse(AuthValidator::validEmail("bad-email"));
    }

    public function testStrongPassword() {
        $this->assertTrue(AuthValidator::strongPassword("Abc@1234"));
        $this->assertFalse(AuthValidator::strongPassword("abc12345"));     // no uppercase + special
        $this->assertFalse(AuthValidator::strongPassword("ABC@1234"));     // no lowercase
        $this->assertFalse(AuthValidator::strongPassword("Abcdefgh@"));    // no digit
        $this->assertFalse(AuthValidator::strongPassword("Abc12345"));     // no special
    }

    public function testAlphabetsOnlyName() {
        $this->assertTrue(AuthValidator::alphabetsOnly("Ali Malik"));
        $this->assertFalse(AuthValidator::alphabetsOnly("Ali123"));
        $this->assertFalse(AuthValidator::alphabetsOnly("Ali@Malik"));
    }

    public function testPasswordsMatch() {
        $this->assertTrue(AuthValidator::passwordsMatch("Abc@1234", "Abc@1234"));
        $this->assertFalse(AuthValidator::passwordsMatch("Abc@1234", "Abc@9999"));
    }
}
