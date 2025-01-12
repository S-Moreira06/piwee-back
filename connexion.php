<?php
require 'db.php';

// Définir les headers pour permettre les requêtes CORS - Cross Origin Ressource Sharing - Partage de ressource cross-origin
header('Access-Control-Allow-Origin: http://localhost:5173'); // Remplace par l'URL exacte de ton client
header('Access-Control-Allow-Methods: POST, GET, OPTIONS'); // Méthodes autorisées
header('Access-Control-Allow-Headers: Content-Type'); // Headers autorisés
header('Content-Type: application/json');

//ajout suite a une erreur preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Vérifier la méthode de la requête
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Récupérer les données JSON envoyées
        $input = json_decode(file_get_contents('php://input'), true);
        $nom = $input['name'] ?? '';

        if (empty($nom)) {
            echo json_encode(['error' => 'Le champ "nom" est requis.']);
            http_response_code(400);
            exit();
        }

        $query = $pdo->prepare('SELECT * FROM users WHERE firstname=:nom');
        $query->bindParam(':nom', $nom, PDO::PARAM_STR);
        $query->execute();
        $data = $query->fetchAll(PDO::FETCH_ASSOC);// Fetch_obj : permet de retourner un resultat sous forme dobjet uniquement , ce qui permet d'avoir le resultat affiché uniquement sous forme clé => valeur

        // Vérification si un utilisateur existe
        if ($data) {
            echo json_encode(['success' => true, 'message' => 'Utilisateur trouvé.', 'data' => $data]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Utilisateur introuvable.']);
        }
    } catch (PDOException $e) {
        // Gérer les erreurs
        echo json_encode(['error' => 'Erreur lors de la récupération des données : ' . $e->getMessage()]);
    }
} else {
        http_response_code(405); // Méthode non autorisée
        echo json_encode(['error' => 'Méthode non autorisée.']);
};