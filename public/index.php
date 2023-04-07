<?php

// Charge l'autoloader PSR-4 de Composer
require_once __DIR__ . '/../vendor/autoload.php';

use App\Config\Dbinitializer;
use Symfony\Component\Dotenv\Dotenv;
use classes\CRUDController;

// Charge les variables d'environnement
$dotenv = new Dotenv();
$dotenv->loadEnv('.env');

$uri = $_SERVER['REQUEST_URI'];
$httpMethod = $_SERVER['REQUEST_METHOD'];
