<?php
// Habilita CORS
header("Access-Control-Allow-Origin: *"); // Ajuste para o domínio do frontend
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Define o tipo de conteúdo da resposta
header('Content-Type: application/json');

// Inclui o arquivo de configuração do banco de dados
include 'config.php';

// Verifica se o parâmetro 'id' foi passado na URL e se é um número válido
if (!isset($_GET['id']) || !is_numeric($_GET['id']) || $_GET['id'] <= 0) {
    echo json_encode(['message' => 'ID do aluno não fornecido ou inválido.']);
    http_response_code(400); // Código de resposta 400 (Bad Request)
    exit;
}

$id = (int) $_GET['id']; // Garante que o ID seja um número inteiro

// Conexão com o banco de dados
try {
    // Prepara a consulta para buscar os dados do aluno com base no ID
    $stmt = $pdo->prepare("SELECT * FROM alunos WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT); // Usa bindParam para prevenir injeção SQL
    $stmt->execute();

    // Verifica se o aluno foi encontrado
    if ($stmt->rowCount() > 0) {
        $aluno = $stmt->fetch(PDO::FETCH_ASSOC); // Retorna o aluno como um array associativo
        echo json_encode($aluno); // Envia os dados do aluno em formato JSON
    } else {
        echo json_encode(['message' => 'Aluno não encontrado.']);
        http_response_code(404); // Código de resposta 404 (Not Found)
    }
} catch (PDOException $e) {
    // Caso haja erro na consulta ao banco
    // Em produção, não devemos mostrar mensagens de erro detalhadas para o cliente.
    echo json_encode(['message' => 'Erro ao conectar ao banco de dados.']);
    http_response_code(500); // Código de resposta 500 (Internal Server Error)
}
?>
