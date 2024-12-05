<?php
// Habilita CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Define o tipo de conteúdo da resposta
header('Content-Type: application/json');

// Resposta a requisições OPTIONS
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(204);
    exit;
}

// Inclui o arquivo de configuração do banco de dados
include 'config.php';

// Obtém os dados enviados em formato JSON
$data = json_decode(file_get_contents('php://input'), true);

// Valida os campos obrigatórios
if (empty($data['id']) || empty($data['nome']) || empty($data['usuario']) || empty($data['email']) || empty($data['data_nascimento'])) {
    echo json_encode(['message' => 'Todos os campos são obrigatórios.']);
    http_response_code(400);
    exit;
}

// Prepara a consulta de atualização do aluno
$stmt = $pdo->prepare("UPDATE alunos SET nome = ?, usuario = ?, email = ?, data_nascimento = ? WHERE id = ?");
$result = $stmt->execute([$data['nome'], $data['usuario'], $data['email'], $data['data_nascimento'], $data['id']]);

if ($result) {
    echo json_encode(['message' => 'Aluno atualizado com sucesso.']);
} else {
    echo json_encode(['message' => 'Erro ao atualizar o aluno.']);
    http_response_code(500);
}
?>
