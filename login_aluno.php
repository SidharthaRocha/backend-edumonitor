<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

include 'config.php'; // Inclui a configuração do banco de dados

$data = json_decode(file_get_contents('php://input'), true);

// Verifica se os campos estão preenchidos
if (empty($data['email']) || empty($data['senha'])) {
    http_response_code(400);
    echo json_encode(['message' => 'E-mail e senha são obrigatórios.']);
    exit;
}

// Verifica se o e-mail tem o formato correto
if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['message' => 'E-mail inválido.']);
    exit;
}

try {
    // Verifica se o aluno existe no banco de dados
    $stmt = $pdo->prepare("SELECT * FROM alunos WHERE email = ?");
    $stmt->execute([$data['email']]);
    $aluno = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($aluno && password_verify($data['senha'], $aluno['senha'])) {
        // Login bem-sucedido, inclui o nome do aluno na resposta
        echo json_encode([
            'message' => 'Login bem-sucedido.',
            'userType' => 'aluno', // Especifica que é um aluno
            'userName' => $aluno['nome'], // Nome do aluno para ser armazenado no localStorage
            'aluno' => $aluno // Retorna as informações do aluno, se necessário
        ]);
    } else {
        http_response_code(401);
        echo json_encode(['message' => 'E-mail ou senha incorretos.']);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['message' => 'Erro ao conectar ao banco de dados: ' . $e->getMessage()]);
}
?>
