<?php
class AuthValidator {
    public static function validEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    public static function strongPassword($pass) {
        return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,}$/', $pass) === 1;
    }

    public static function alphabetsOnly($name) {
        return preg_match('/^[A-Za-z ]+$/', $name) === 1;
    }

    public static function passwordsMatch($p1, $p2) {
        return $p1 === $p2;
    }
}
