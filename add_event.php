<?php
// Habilita CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Define o tipo de conteúdo da resposta
header('Content-Type: application/json');

// Conexão com o banco de dados
include 'config.php';

// Recebe os dados do evento em formato JSON
$data = json_decode(file_get_contents('php://input'), true);

// Valida os dados
if (empty($data['title']) || empty($data['start']) || empty($data['end'])) {
    echo json_encode(['status' => 'error', 'message' => 'Campos obrigatórios não fornecidos.']);
    http_response_code(400); // Código de erro 400
    exit;
}

// Prepara a consulta para adicionar o evento
$stmt = $pdo->prepare("INSERT INTO events (title, start, end, description) VALUES (?, ?, ?, ?)");
$result = $stmt->execute([$data['title'], $data['start'], $data['end'], $data['description']]);

if ($result) {
    echo json_encode(['status' => 'success', 'message' => 'Evento adicionado com sucesso']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Erro ao adicionar evento']);
}
?>
