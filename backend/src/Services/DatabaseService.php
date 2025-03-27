<?php
namespace Src\Services;

use PDO;
use PDOException;

class DatabaseService
{
    private static $host = 'db';
    private static $db_name = 'tasks_db';
    private static $username = 'user';
    private static $password = 'password';

    private static $conn;

    public static function getConnection()
    {
        if (self::$conn === null) {
            try {
                $dsn = "mysql:host=" . self::$host . ";dbname=" . self::$db_name . ";charset=utf8";
                self::$conn = new PDO($dsn, self::$username, self::$password);
                self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                echo "Connection error: " . $e->getMessage();
                exit;
            }
        }
        return self::$conn;
    }
}
