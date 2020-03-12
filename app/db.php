<?php
class db
{
    private static $dbi = null;
    private function __construct() {
    }
    public static function getDb() {
        if(is_null(self::$dbi)) {
            $DATABASE_HOST = 'localhost';
            $DATABASE_USER = 'root';
            $DATABASE_PASS = 'system32';
            $DATABASE_NAME = 'educato';
            try {
                self::$dbi = new PDO('mysql:host=' . $DATABASE_HOST . ';dbname=' . $DATABASE_NAME . ';charset=utf8', $DATABASE_USER, $DATABASE_PASS);
            } catch (PDOException $exception) {
                // If there is an error with the connection, stop the script and display the error.
                exit('Failed to connect to database!');
            }
        }
        return self::$dbi;
    }
}
?>