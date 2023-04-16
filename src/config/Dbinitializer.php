<?php

namespace App\Config;

use Exception;
use PDO;

class Dbinitializer
{
    static function getPdoInstance(): PDO
    {
        if (!isset( $_ENV['DB_PORT']) || $_ENV['DB_HOST'] || $_ENV['DB_NAME'] || $_ENV['DB_CHARSET'] || $_ENV['DB_USER'] || $_ENV['DB_PASS']) {
            throw new Exception('Unable to load configuration, please check your environment');
        }
        // Initialisation BDD
        $dsn = "mysql:host=" . $_ENV['DB_HOST'] . ";port=" . $_ENV['DB_PORT'] . ";dbname=" . $_ENV['DB_NAME'] . ";charset=" . $_ENV['DB_CHARSET'];

        return new PDO(
            $dsn,
            $_ENV['DB_USER'],
            $_ENV['DB_PASS']);
    }
}