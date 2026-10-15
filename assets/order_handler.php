<?php
header('Content-Type: application/json; charset=utf-8');

// Récupération de la requête POST JSON
$rawData = file_get_contents('php://input');
$orderData = json_decode($rawData, true);

if (!$orderData || empty($orderData['items'])) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Données de commande invalides.']);
    exit;
}

// Extraction et sécurisation des données
$clientName    = htmlspecialchars(trim($orderData['customer_name'] ?? ''));
$clientPhone   = htmlspecialchars(trim($orderData['customer_phone'] ?? ''));
$clientAddress = htmlspecialchars(trim($orderData['customer_address'] ?? ''));
$totalAmount   = floatval($orderData['total_amount'] ?? 0);
$currency      = 'DZD';

// Exemple de sauvegarde dans la base de données (PDO MySQL)
try {
    /*
    $pdo = new PDO('mysql:host=localhost;dbname=pizzeria;charset=utf8mb4', 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    $stmt = $pdo->prepare("INSERT INTO orders (client_name, phone, address, total, currency, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
    $stmt->execute([$clientName, $clientPhone, $clientAddress, $totalAmount, $currency]);
    $orderId = $pdo->lastInsertId();
    */

    // Réponse de succès
    echo json_encode([
        'status' => 'success',
        'message' => 'Commande confirmée avec succès.',
        'order_id' => rand(1000, 9999),
        'total' => $totalAmount . ' ' . $currency
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Erreur serveur lors de l\'enregistrement.']);
}