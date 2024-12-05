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

if (empty($data['email']) || empty($data['senha'])) {
    http_response_code(400);
    echo json_encode(['message' => 'E-mail e senha são obrigatórios.']);
    exit;
}

if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['message' => 'E-mail inválido.']);
    exit;
}

// Primeiro, tentamos encontrar o usuário na tabela de professores
$stmt = $pdo->prepare("SELECT * FROM professores WHERE email = ?");
$stmt->execute([$data['email']]);
$professor = $stmt->fetch(PDO::FETCH_ASSOC);

// Se o usuário for encontrado na tabela de professores, verificamos a senha
if ($professor && password_verify($data['senha'], $professor['senha'])) {
    http_response_code(200);
    echo json_encode([
        'message' => 'Login bem-sucedido.',
        'userType' => 'professor', // Retorna o tipo de usuário como 'professor'
        'professor' => $professor
    ]);
    exit;
}

// Caso não seja professor, tentamos na tabela de alunos
$stmt = $pdo->prepare("SELECT * FROM alunos WHERE email = ?");
$stmt->execute([$data['email']]);
$aluno = $stmt->fetch(PDO::FETCH_ASSOC);

// Se o usuário for encontrado na tabela de alunos, verificamos a senha
if ($aluno && password_verify($data['senha'], $aluno['senha'])) {
    http_response_code(200);
    echo json_encode([
        'message' => 'Login bem-sucedido.',
        'userType' => 'aluno', // Retorna o tipo de usuário como 'aluno'
        'aluno' => $aluno
    ]);
    exit;
}

// Se não encontrar nenhum dos dois usuários, retornamos um erro de login
http_response_code(401);
echo json_encode(['message' => 'E-mail ou senha incorretos.']);
?>
