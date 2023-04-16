<?php

class productCRUD
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function GetAllProduct()
    { 
            $query = "SELECT * FROM products";
            $stmt = $this->pdo->query("SELECT * FROM products");
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($result);
    }

    public function addProduct($name, $price, $description)
    {
            $data = json_decode(file_get_contents("php://input"), true);
            $query = "INSERT INTO products (name, base_price, description) VALUES (?, ?, ?)";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([
                $name,
                $price,
                $description,
            ]);
            http_response_code(204);
    }

    public function deleteProduct($id)
    {
            if (!isset($id)) {
                http_response_code(400);
                echo json_encode(array('message' => 'ID is required for DELETE operation.'));
                return;
            }

            $query = "DELETE FROM products WHERE id=?";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$id]);
            $affectedRows = $stmt->rowCount();

            if ($affectedRows > 0) {
                http_response_code(200);
                echo json_encode(array('message' => 'livre deleted successfully.'));
            } else {
                http_response_code(404);
                echo json_encode(array('message' => 'livre not found.'));
            }
    }
}
