<?php

// Habilita CORS
header("Access-Control-Allow-Origin: *"); // Ajuste para o domínio do frontend
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Define o tipo de conteúdo da resposta
header('Content-Type: application/json');

// Resposta a requisições OPTIONS
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(204); // No Content
    exit; // Termina a execução para requisições OPTIONS
}

// Inclui o arquivo de configuração do banco de dados
include 'config.php';

// Obtém os dados enviados em formato JSON
$data = json_decode(file_get_contents('php://input'), true);

// Valida os campos obrigatórios
if (empty($data['nome']) || empty($data['usuario']) || empty($data['email']) || empty($data['data_nascimento']) || empty($data['senha'])) {
    echo json_encode(['message' => 'Todos os campos são obrigatórios.']);
    http_response_code(400); // Código de resposta 400 (Bad Request)
    exit;
}

// Verifica se o email já existe
$stmt = $pdo->prepare("SELECT * FROM alunos WHERE email = ?");
$stmt->execute([$data['email']]);
if ($stmt->rowCount() > 0) {
    echo json_encode(['message' => 'Este e-mail já está cadastrado.']);
    http_response_code(409); // Conflito
    exit;
}

// Faz o hash da senha
$senhaHash = password_hash($data['senha'], PASSWORD_BCRYPT);

try {
    // Prepara e executa a inserção no banco de dados
    $stmt = $pdo->prepare("INSERT INTO alunos (nome, usuario, email, data_nascimento, senha) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$data['nome'], $data['usuario'], $data['email'], $data['data_nascimento'], $senhaHash]);

    // Resposta de sucesso
    echo json_encode(['message' => 'Aluno cadastrado com sucesso.']);
} catch (PDOException $e) {
    // Resposta de erro
    echo json_encode(['message' => 'Erro ao cadastrar: ' . $e->getMessage()]);
    http_response_code(500); // Código de resposta 500 (Internal Server Error)
}
?>
