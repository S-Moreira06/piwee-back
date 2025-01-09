<?php
include_once 'db.php';
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $query = $pdo->query('SELECT * FROM product');
        $data = $query->fetchAll(PDO::FETCH_OBJ);
        echo json_encode($data);
    }
    catch(PDOException $e) {
        echo json_encode(['error' => 'Erreur lors de la récupération des données : ' . $e->getMessage()]);
    }
} else {
    http_response_code(405); //405: Méthode non autorisée
    echo json_encode(['error' => 'Méthode non autorisée.']);
};