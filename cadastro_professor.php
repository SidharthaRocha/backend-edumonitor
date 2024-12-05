<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

include 'config.php';

$data = json_decode(file_get_contents('php://input'), true);

if (empty($data['nome']) || empty($data['usuario']) || empty($data['email']) || empty($data['data_nascimento']) || empty($data['senha']) || empty($data['disciplina'])) {
    echo json_encode(['message' => 'Todos os campos são obrigatórios.']);
    http_response_code(400); // Código de resposta 400 (Bad Request)
    exit;
}

// Verifica se o e-mail já existe
$stmt = $pdo->prepare("SELECT * FROM professores WHERE email = ?");
$stmt->execute([$data['email']]);
if ($stmt->rowCount() > 0) {
    echo json_encode(['message' => 'E-mail já cadastrado.']);
    http_response_code(400); // Bad Request
    exit;
}

// Faz o hash da senha
$senhaHash = password_hash($data['senha'], PASSWORD_BCRYPT);

try {
    // Prepara e executa a inserção no banco de dados
    $stmt = $pdo->prepare("INSERT INTO professores (nome, usuario, email, data_nascimento, senha, disciplina) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$data['nome'], $data['usuario'], $data['email'], $data['data_nascimento'], $senhaHash, $data['disciplina']]);
    
    // Resposta de sucesso
    echo json_encode(['message' => 'Professor cadastrado com sucesso.']);
} catch (PDOException $e) {
    echo json_encode(['message' => 'Erro ao cadastrar: ' . $e->getMessage()]);
    http_response_code(500); // Código de resposta 500 (Internal Server Error)
}
?>
