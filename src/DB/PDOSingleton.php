<?php

declare(strict_types=1);

namespace Matheus\TestePleno\DB;

use PDO;
use PDOException;

class PDOSingleton
{
    private static $db_connection;
    private static $db_host;
    private static $db_name;
    private static $db_user;
    private static $db_password;

    private static $connection;

    public static function getConnection (): PDO
    {
    
        if(!self::$connection)
        {
            self::$db_connection = getenv('DB_CONNECTION') ? getenv('DB_CONNECTION') : 'mysql';
            self::$db_host = getenv('DB_HOST') ? getenv('DB_HOST') : 'db';
            self::$db_name = getenv('DB_NAME') ? getenv('DB_NAME') : 'app';
            self::$db_user = getenv('DB_USER') ? getenv('DB_USER') : 'app_user';
            self::$db_password = getenv('DB_PASSWORD') ? getenv('DB_PASSWORD') : 'password';

            try {
                return self::$connection = new PDO(
                    self::$db_connection.":host=".self::$db_host.";dbname=".self::$db_name, self::$db_user, self::$db_password
                );
            } catch (PDOException $exception) {

                throw new PDOException($exception->getMessage());
            }
        }

        return self::$connection;
    }
}