<?php
header('Content-Type: application/json');

// Permitir requisições de qualquer origem
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

// Verificar método HTTP
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['message' => 'Método não permitido']);
    exit;
}

// Capturar os dados JSON da requisição
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['email'], $data['sms'])) {
    http_response_code(400);
    echo json_encode(['message' => 'Dados incompletos']);
    exit;
}

// Conectar ao banco de dados
require 'config.php';

$email = $data['email'];
$smsNotificacao = isset($data['sms']) && $data['sms'] ? 1 : 0;
$emailNotificacao = isset($data['email']) && $data['email'] ? 1 : 0;

// Usando prepared statement para evitar SQL Injection
$sql = "UPDATE alunos SET notificacao_email = ?, notificacao_sms = ? WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iis", $emailNotificacao, $smsNotificacao, $email); // i -> integer, s -> string

if ($stmt->execute()) {
    echo json_encode(['message' => 'Configurações de notificações salvas com sucesso']);
} else {
    http_response_code(500);
    echo json_encode(['message' => 'Erro ao salvar configurações']);
}

$stmt->close();
$conn->close();
?>
