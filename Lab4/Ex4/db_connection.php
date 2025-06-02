<?php
class DatabaseConnection {
    private $host = 'localhost';
    private $username = 'root';
    private $password = 'Miliano';
    private $database = 'LibraryfourDB';
    private $connection;

    public function __construct() {
        try {
            if ($this->host === null || $this->username === null || $this->password === null || $this->database === null) {
                throw new InvalidArgumentException('Database connection parameters are not set.');
            }
            $this->connection = new PDO(
                "mysql:host={$this->host};dbname={$this->database}",
                $this->username,
                $this->password
            );
            if ($this->connection === null) {
                throw new RuntimeException('Failed to establish database connection.');
            }
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        } catch (InvalidArgumentException $e) {
            die("Invalid database connection parameters: " . $e->getMessage());
        } catch (RuntimeException $e) {
            die("Failed to establish database connection: " . $e->getMessage());
        }
    }

    public function getConnection() {
        return $this->connection;
    }
}