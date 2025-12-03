<?php
class Database {
    private $host = 'localhost';
    private $db_name = 'fincrade_admin';
    private $username = 'root';
    private $password = '';
    private $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            // Try MySQL first
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            // Fallback to SQLite for testing
            try {
                $this->conn = new PDO("sqlite:" . __DIR__ . "/../fincrade_admin.db");
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch(PDOException $sqliteException) {
                echo "Connection error (both MySQL and SQLite failed): " . $exception->getMessage() . " | " . $sqliteException->getMessage();
            }
        }
        return $this->conn;
    }
}
?>
