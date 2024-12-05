<?php

// Habilita CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
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
if (empty($data['email']) || empty($data['senha']) || empty($data['tipo'])) {
    http_response_code(400);
    echo json_encode(['message' => 'E-mail, senha e tipo são obrigatórios.']);
    exit;
}

$email = $data['email'];
$senha = $data['senha'];
$tipo = $data['tipo']; // "professor" ou "aluno"

// Validar se o tipo de usuário é válido
if ($tipo !== 'professor' && $tipo !== 'aluno') {
    http_response_code(400);
    echo json_encode(['message' => 'Tipo de usuário inválido.']);
    exit;
}

try {
    // Determinar qual tabela será consultada
    $tabela = ($tipo === 'professor') ? 'professores' : 'alunos';

    // Preparar a consulta para verificar as credenciais
    $stmt = $pdo->prepare("SELECT * FROM $tabela WHERE email = ?");
    $stmt->execute([$email]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verificar se o usuário existe e se a senha está correta
    if ($usuario && password_verify($senha, $usuario['senha'])) {
        http_response_code(200);
        echo json_encode(['message' => 'Login bem-sucedido.']);
    } else {
        http_response_code(401);
        echo json_encode(['message' => 'E-mail ou senha incorretos.']);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['message' => 'Erro ao conectar ao banco de dados: ' . $e->getMessage()]);
}
?>
