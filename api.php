<?php
require 'db.php';

// Définir les headers pour permettre les requêtes CORS - Cross Origin Ressource Sharing - Partage de ressource cross-origin
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Vérifier la méthode de la requête
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $query = $pdo->query('SELECT * FROM users');
        $data = $query->fetchAll(PDO::FETCH_ASSOC);// Fetch_obj : permet de retourner un resultat sous forme dobjet uniquement , ce qui permet d'avoir le resultat affiché uniquement sous forme clé => valeur

        // Retourner les données en format JSON
        echo json_encode($data);
    } catch (PDOException $e) {
        // Gérer les erreurs
        echo json_encode(['error' => 'Erreur lors de la récupération des données : ' . $e->getMessage()]);
    }
} else {
    // Retourner une erreur si la méthode n'est pas GET
    http_response_code(405); // Méthode non autorisée
    echo json_encode(['error' => 'Méthode non autorisée.']);
};

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    try {
        $query = $pdo->prepare('SELECT * FROM your_table_name WHERE id = ?');
        $query->execute([$id]);
        $data = $query->fetch(PDO::FETCH_ASSOC);

        echo json_encode($data);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Erreur : ' . $e->getMessage()]);
    }
}

