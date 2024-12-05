<?php

// Habilita CORS
header("Access-Control-Allow-Origin: *"); // Ajuste para o domínio do frontend
header("Access-Control-Allow-Methods: GET, POST, DELETE, PUT, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Define o tipo de conteúdo da resposta
header('Content-Type: application/json');

// Resposta a requisições OPTIONS (preflight request)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(204); // No Content
    exit; // Termina a execução para requisições OPTIONS
}

// Inclui o arquivo de configuração do banco de dados
include 'config.php';

// Obtém o método da requisição
$method = $_SERVER['REQUEST_METHOD'];

// Função para enviar erro em JSON
function sendError($message, $code = 400) {
    http_response_code($code);
    echo json_encode(['success' => false, 'message' => $message]);
    exit;
}

// Função para buscar alunos (GET)
if ($method == 'GET') {
    $searchQuery = $_GET['search'] ?? ''; // O parâmetro de busca pode vir na URL

    // Verifica se o parâmetro de busca está vazio
    if (empty($searchQuery)) {
        echo json_encode(['message' => 'Por favor, insira um termo de busca.']);
        http_response_code(400); // Bad Request
        exit;
    }

    // Prepara a consulta SQL para buscar alunos com base no nome ou usuário
    $stmt = $pdo->prepare("SELECT * FROM alunos WHERE nome LIKE :search OR usuario LIKE :search");
    $stmt->execute(['search' => "%$searchQuery%"]); // Executa a consulta com o parâmetro de busca

    // Obtém os resultados
    $alunos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Verifica se algum aluno foi encontrado
    if (count($alunos) > 0) {
        echo json_encode($alunos);
    } else {
        sendError('Nenhum aluno encontrado.', 404); // Not Found
    }
}

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['nome'], $data['email'], $data['status'])) {
    echo json_encode(['message' => 'Todos os campos são obrigatórios.']);
    http_response_code(400); // Bad Request
    exit;
}

$nome = $data['nome'];
$email = $data['email'];
$status = $data['status'];

// Verifique se o e-mail já está cadastrado
$stmt = $pdo->prepare("SELECT id FROM alunos WHERE email = :email");
$stmt->execute(['email' => $email]);
$existingAluno = $stmt->fetch(PDO::FETCH_ASSOC);
if ($existingAluno) {
    echo json_encode(['message' => 'Este e-mail já está cadastrado.']);
    http_response_code(409); // Conflict
    exit;
}

// Prepara a consulta SQL para inserir o aluno
$stmt = $pdo->prepare("INSERT INTO alunos (nome, email, status) VALUES (:nome, :email, :status)");
$stmt->execute(['nome' => $nome, 'email' => $email, 'status' => $status]);

$newAlunoId = $pdo->lastInsertId();

    // Retorna o sucesso e os dados do novo aluno
    echo json_encode([
        'success' => true,
        'message' => 'Aluno adicionado com sucesso!',
        'aluno' => [
            'id' => $newAlunoId,
            'nome' => $nome,
            'email' => $email,
            'status' => $status
        ]
    ]);
    exit;


// Função para remover aluno (DELETE)
if ($method == 'DELETE') {
    $id = $_GET['id'] ?? null; // Obtém o ID do aluno pela URL

    if (empty($id)) {
        sendError('ID do aluno não fornecido.', 400);
    }

    // Prepara a consulta SQL para remover o aluno
    $stmt = $pdo->prepare("DELETE FROM alunos WHERE id = :id");
    $stmt->execute(['id' => $id]);

    // Verifica se a remoção foi bem-sucedida
    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true, 'message' => 'Aluno removido com sucesso.']);
    } else {
        sendError('Aluno não encontrado ou já removido.', 404);
    }
    exit;
}

// Função para editar aluno (PUT)
if ($method == 'PUT') {
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $_GET['id'] ?? null;

    if (empty($id)) {
        sendError('ID do aluno não fornecido.', 400);
    }

    if (!isset($data['nome'], $data['email'], $data['status'])) {
        sendError('Por favor, preencha todos os campos necessários.', 400);
    }

    $nome = $data['nome'];
    $email = $data['email'];
    $status = $data['status'];

    // Prepara a consulta SQL para atualizar o aluno
    $stmt = $pdo->prepare("UPDATE alunos SET nome = :nome, email = :email, status = :status WHERE id = :id");
    $stmt->execute(['id' => $id, 'nome' => $nome, 'email' => $email, 'status' => $status]);

    // Verifica se o aluno foi atualizado
    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true, 'message' => 'Aluno atualizado com sucesso.']);
    } else {
        sendError('Aluno não encontrado ou dados não modificados.', 404);
    }
    exit;
}

// Se o método HTTP não for suportado
sendError('Método não suportado.', 405); // Method Not Allowed
?>
