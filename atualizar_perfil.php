<?php
// Habilitar CORS (se necessário)
header("Access-Control-Allow-Origin: *");  // Permitir qualquer origem
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS"); // Métodos permitidos
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // Cabeçalhos permitidos

// Tratar requisição OPTIONS
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit();
}

// Incluir a configuração do banco de dados
include('config.php');  // Verifique o caminho correto para o seu arquivo config.php

// Obter o método da requisição
$method = $_SERVER['REQUEST_METHOD'];

// Verificar se é um PUT
if ($method == 'PUT') {
    // Obter o ID do usuário da URL
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
    } else {
        echo json_encode(['message' => 'ID do usuário não fornecido.']);
        exit();
    }

    // Obter os dados da requisição PUT
    parse_str(file_get_contents("php://input"), $data);

    // Validar os campos recebidos
    $nome = isset($data['nome']) ? $data['nome'] : '';
    $email = isset($data['email']) ? $data['email'] : '';
    $senha = isset($data['senha']) ? password_hash($data['senha'], PASSWORD_DEFAULT) : '';  // Hash a senha
    $data_nascimento = isset($data['data_nascimento']) ? $data['data_nascimento'] : '';
    $disciplina = isset($data['disciplina']) ? $data['disciplina'] : '';

    // Validar se os campos estão preenchidos
    if (empty($nome) || empty($email) || empty($senha) || empty($data_nascimento) || empty($disciplina)) {
        echo json_encode(['message' => 'Todos os campos são obrigatórios.']);
        exit();
    }

    try {
        // Preparar a query de atualização
        $sql = "UPDATE usuarios SET nome = ?, email = ?, senha = ?, data_nascimento = ?, disciplina = ? WHERE id = ?";

        // Usar prepared statement para evitar SQL Injection
        $stmt = $conn->prepare($sql);

        // Executar a query
        $stmt->execute([$nome, $email, $senha, $data_nascimento, $disciplina, $id]);

        // Verificar se foi afetado pelo menos 1 registro
        if ($stmt->rowCount() > 0) {
            echo json_encode(['message' => 'Perfil atualizado com sucesso!']);
        } else {
            echo json_encode(['message' => 'Nenhum dado foi atualizado.']);
        }
    } catch (PDOException $e) {
        // Em caso de erro na execução da query
        echo json_encode(['message' => 'Erro ao atualizar perfil. Detalhes: ' . $e->getMessage()]);
    }
}

// Fechar a conexão PDO
$conn = null;
?>
