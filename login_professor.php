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
    echo json_encode(['message' => 'E-mail ou senha inválido.']);
    exit;
}

try {
    // Verifica se o professor existe no banco de dados
    $stmt = $pdo->prepare("SELECT * FROM professores WHERE email = ?");
    $stmt->execute([$data['email']]);
    $professor = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($professor && password_verify($data['senha'], $professor['senha'])) {
        // Login bem-sucedido
        echo json_encode([
            'message' => 'Login bem-sucedido.',
            'userType' => 'professor', // Especifica que é um professor
            'userName' => $professor['nome'], // Adiciona o nome do professor
            'professor' => $professor // Pode retornar outras informações sobre o professor, se necessário
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
