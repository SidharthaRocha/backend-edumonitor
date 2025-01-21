<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT feedback, sugestao, DATE_FORMAT(data_envio, '%d/%m/%Y %H:%i') AS data_envio FROM feedbacks ORDER BY data_envio DESC");
    $stmt->execute();
    $feedbacks = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'status' => 'success',
        'data' => $feedbacks
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Erro ao buscar os dados: ' . $e->getMessage()
    ]);
}
?>
