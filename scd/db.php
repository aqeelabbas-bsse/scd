<?php
/*  
   DB Connection File (Safe & Exception Based)
*/

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {

    $host = "localhost";
    $user = "root";
    $pass = "";
    $dbname = "scd_db";

    // Try to connect
    $conn = new mysqli($host, $user, $pass, $dbname);
    $conn->set_charset("utf8mb4"); // Recommended

} catch (Throwable $e) {

    // Log actual error
    error_log("DB Connection Error: " . $e->getMessage());

    // Show safe message to user
    echo "<script>
            alert('Unable to connect to database. Please try again later.');
          </script>";

    exit;
}
?>