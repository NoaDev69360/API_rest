<?php

// Charge l'autoloader PSR-4 de Composer
require_once __DIR__ . '/../vendor/autoload.php';


use App\Config\Dbinitializer;
use Symfony\Component\Dotenv\Dotenv;
use App\classes\functionsCRUD;

// Charge les variables d'environnement
$dotenv = new Dotenv();
$dotenv->loadEnv('.env');

$pdo = DbInitializer::getPdoInstance();
require_once 'functionsCRUD.php';
$productCrud = new productCRUD($pdo);

$uri = $_SERVER['REQUEST_URI'];
$httpMethod = $_SERVER['REQUEST_METHOD'];

if ($uri === '/products' && $httpMethod === 'GET') {
    echo json_encode($productCrud->GetAllProduct());
    exit;
  }

  if ($uri === '/product' && $httpMethod === 'POST') {
    echo json_encode($productCrud->addProduct($_POST['name'], $_POST['price'], $_POST['description']));
    exit;
  }

  if ($uri === '/product' && $httpMethod === 'DELETE') {
    echo json_encode($productCrud->deleteProduct($_POST['id']));
    exit;
  }


