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
    http_response_code(400); // Erro 400 - Bad Request
    echo json_encode(['message' => 'E-mail e senha são obrigatórios.']);
    exit;
}

// Valida o formato do e-mail
if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
    http_response_code(400); // Erro 400 - Bad Request
    echo json_encode(['message' => 'E-mail inválido.']);
    exit;
}

try {
    // Prepara a consulta para verificar se o aluno existe no banco de dados
    $stmt = $pdo->prepare("SELECT * FROM alunos WHERE email = ?");
    $stmt->execute([$data['email']]);
    $aluno = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verifica se o aluno existe e se a senha está correta
    if ($aluno && password_verify($data['senha'], $aluno['senha'])) {
        // Login bem-sucedido, retorna os dados do aluno
        echo json_encode([
            'message' => 'Login bem-sucedido.',
            'userType' => 'aluno', // Tipo de usuário
            'userName' => $aluno['nome'], // Nome do aluno para ser usado no frontend
            'aluno' => $aluno // Retorna as informações do aluno, se necessário
        ]);
    } else {
        // Caso o e-mail ou senha estejam incorretos
        http_response_code(401); // Erro 401 - Unauthorized
        echo json_encode(['message' => 'E-mail ou senha incorretos.']);
    }
} catch (PDOException $e) {
    // Caso ocorra um erro ao conectar ao banco de dados
    http_response_code(500); // Erro 500 - Internal Server Error
    echo json_encode(['message' => 'Erro ao conectar ao banco de dados: ' . $e->getMessage()]);
}
?>
