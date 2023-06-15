<?php

/**
 * connection single-ton
 */
class Database
{
    /* Db config */
    /* Db handler */
    private static $conn = null;

    /**
     * Constructor
     */
    private function __construct()
    {
        self::$conn = null;
    }

    public static function close(): void
    {
        self::$conn->close();
    }

    /**
     * Connect to DB
     */
    public static function connectDB(): void
    {
        try {
            $servername = "web-frow.database.windows.net";
            $database = "gabriel";
            $username = "web-admin";
            $password = "Fruits&Vegetables";

            $conn = new PDO("sqlsrv:server=$servername;database=$database", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            self::$conn = $conn;
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }

    public static function getConnection()
    {
        if (self::$conn == null) {
            self::connectDB();
        }
        return self::$conn;
    }
}