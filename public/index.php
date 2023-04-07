<?php

// Charge l'autoloader PSR-4 de Composer
require_once __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Dotenv\Dotenv;

// Définit un gestionnaire d'exceptions au niveau global
set_exception_handler(function (Throwable $e) {
  http_response_code(500);
  echo json_encode([
    'error' => 'Une erreur est survenue',
    'code' => $e->getCode(),
    'message' => $e->getMessage()
  ]);
});

// Charge les variables d'environnement
$dotenv = new Dotenv();
$dotenv->loadEnv('.env');

// Initialisation BDD
$dsn = "mysql:host=" . $_ENV['DB_HOST'] .
  ";port=" . $_ENV['DB_PORT'] .
  ";dbname=" . $_ENV['DB_NAME'] .
  ";charset=" . $_ENV['DB_CHARSET'];

$pdo = new PDO($dsn, $_ENV['DB_USER'], $_ENV['DB_PASS']);

$uri = $_SERVER['REQUEST_URI'];
$httpMethod = $_SERVER['REQUEST_METHOD'];

/*----------------------SELECTIONNE------------------------*/

if ($uri === '/products' && $httpMethod === 'GET') {
  $query = "SELECT * FROM products";
  $stmt = $this->pdo->prepare($query);
  $stmt->execute();
  $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
  echo json_encode($result);
}

/*---------------------------AJOUTER------------------------*/

if ($uri === '/products' && $httpMethod === 'POST') {
  $data = json_decode(file_get_contents("php://input"), true);

  $query = "INSERT INTO products (name, base_price, description) VALUES (?, ?, ?)";

  $stmt = $this->pdo->prepare($query);
  $stmt->execute([
      $data['name'],
      $data['base_price'],
      $data['description']
  ]);
  http_response_code(204);
}

/*----------------------------supprimer-------------------*/

if ($uri === '/products' && $httpMethod === 'DELETE') {
  // Vérifier si l'ID est présent dans les paramètres de la requête
  if (!isset($_GET['id'])) {
      // Envoyer une réponse d'erreur appropriée si l'ID est manquant
      http_response_code(400);
      echo json_encode(array('message' => 'ID is required for DELETE operation.'));
      return;
  }

  $id = $_GET['id']; // Récupérer l'ID à partir des paramètres de la requête

  // Préparer et exécuter la requête DELETE
  $query = "DELETE FROM products WHERE id=?";
  $stmt = $this->pdo->prepare($query);
  $stmt->execute([$id]); // Passer l'ID comme valeur à supprimer
  $affectedRows = $stmt->rowCount(); // Récupérer le nombre de lignes affectées

  if ($affectedRows > 0) {
      // Envoyer une réponse de succès si la suppression a réussi
      http_response_code(200);
      echo json_encode(array('message' => 'Product deleted successfully.'));
  } else {
      // Envoyer une réponse d'erreur si la suppression a échoué
      http_response_code(404);
      echo json_encode(array('message' => 'Product not found.'));
  }
}