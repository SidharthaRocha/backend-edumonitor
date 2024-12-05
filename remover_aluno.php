<?php
// Habilita CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: DELETE, OPTIONS");
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
if (empty($data['id'])) {
    echo json_encode(['message' => 'O ID do aluno é obrigatório.']);
    http_response_code(400);
    exit;
}

// Prepara a consulta para remover o aluno
$stmt = $pdo->prepare("DELETE FROM alunos WHERE id = ?");
$result = $stmt->execute([$data['id']]);

if ($result) {
    echo json_encode(['message' => 'Aluno removido com sucesso.']);
} else {
    echo json_encode(['message' => 'Erro ao remover o aluno.']);
    http_response_code(500);
}
?>
