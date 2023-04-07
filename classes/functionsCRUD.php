<?php

class CRUDController
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function selectUser($uri, $httpMethod)
    {
        if ($uri === '/products' && $httpMethod === 'GET') {
            $query = "SELECT * FROM products";
            $stmt = $this->pdo->query("SELECT * FROM products");
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($result);
        }
    }

    public function addProduct($uri, $httpMethod)
    {
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
    }

    public function deleteUser($uri, $httpMethod)
    {
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
    }
}
