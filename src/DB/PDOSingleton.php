<?php

declare(strict_types=1);

namespace Matheus\TestePleno\DB;

use PDO;
use PDOException;

class PDOSingleton
{
    private static $connection;

    public static function getConnection (): PDO
    {
        if(!self::$connection)
        {
            try {
                return self::$connection = new PDO(
                    getenv('DB_CONNECTION').":host=".getenv('DB_HOST').";dbname=".getenv("DB_NAME"), getenv('DB_USER'), getenv('DB_PASSWORD')
                );
            } catch (PDOException $exception) {

                throw new PDOException($exception->getMessage());
            }
        }

        return self::$connection;
    }
}