<?php
/**
 *  Singleton Database Connection Class
 *  - Secure
 *  - Exception-based
 *  - One connection reused everywhere
 */

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

class Database {

    private static $instance = null;   // SINGLE INSTANCE
    private $conn;

    private $host = "localhost";
    private $user = "root";
    private $pass = "";
    private $dbname = "scd_db";

    // Private constructor → prevents "new Database()"
    private function __construct() {
        try {
            $this->conn = new mysqli(
                $this->host,
                $this->user,
                $this->pass,
                $this->dbname
            );

            $this->conn->set_charset("utf8mb4");

        } catch (Throwable $e) {
            error_log("DB Connection Error: " . $e->getMessage());
            echo "<script>alert('Database connection error. Try again later.');</script>";
            exit;
        }
    }

    // Singleton Access Point
    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    // Return the mysqli connection
    public function getConnection() {
        return $this->conn;
    }
}

// Make available globally
$db = Database::getInstance();
$conn = $db->getConnection();
?>