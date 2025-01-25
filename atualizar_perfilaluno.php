<?php
header('Content-Type: application/json');

// Permitir requisições de qualquer origem
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Responder à requisição OPTIONS (preflight)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200); // Responde OK à requisição OPTIONS
    exit;
}

// Verificar método HTTP
if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
    http_response_code(405);
    echo json_encode(['message' => 'Método não permitido']);
    exit;
}

// Capturar os dados JSON da requisição
$data = json_decode(file_get_contents('php://input'), true);

// Validar se os dados necessários foram enviados
if (!isset($data['nome'], $data['email'], $data['senha'])) {
    http_response_code(400);
    echo json_encode(['message' => 'Dados incompletos']);
    exit;
}

// Validar formato de e-mail
if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['message' => 'Email inválido']);
    exit;
}

// Conectar ao banco de dados
require 'config.php';

$nome = $data['nome'];
$email = $data['email'];
$senha = password_hash($data['senha'], PASSWORD_DEFAULT);

// Verificar se o aluno existe no banco antes de atualizar
$sql = "SELECT * FROM alunos WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    http_response_code(404);
    echo json_encode(['message' => 'Aluno não encontrado']);
    exit;
}

// Usando prepared statement para atualização dos dados
$sql = "UPDATE alunos SET nome = ?, senha = ? WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $nome, $senha, $email);

if ($stmt->execute()) {
    echo json_encode(['message' => 'Perfil atualizado com sucesso']);
} else {
    // Registrar o erro no log do servidor para facilitar a depuração
    error_log('Erro ao atualizar perfil: ' . $stmt->error);
    http_response_code(500);
    echo json_encode(['message' => 'Erro ao atualizar perfil']);
}

$stmt->close();
$conn->close();
?>
