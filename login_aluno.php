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

try {
    // Verifica se o aluno existe no banco de dados
    $stmt = $pdo->prepare("SELECT * FROM alunos WHERE email = ?");
    $stmt->execute([$data['email']]);
    $aluno = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($aluno && password_verify($data['senha'], $aluno['senha'])) {
        // Login bem-sucedido
        echo json_encode([
            'message' => 'Login bem-sucedido.',
            'userType' => 'aluno', // Especifica que é um aluno
            'aluno' => $aluno // Pode retornar outras informações sobre o aluno, se necessário
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
