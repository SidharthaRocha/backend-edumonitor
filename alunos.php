<?php

// Habilita CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Define o tipo de conteúdo como JSON
header('Content-Type: application/json');

// Inclui o arquivo de configuração do banco de dados
include 'config.php';

// Verifica se a requisição é GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        // Consulta para buscar todos os alunos
        $stmt = $pdo->query("SELECT id, nome, usuario, email, data_nascimento FROM alunos");
        $alunos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Adiciona notas fictícias para exemplo (caso ainda não implemente notas no banco)
        foreach ($alunos as &$aluno) {
            $aluno['notas'] = [8.5, 7.0, 9.0]; // Substitua por uma consulta real se houver notas
        }

        // Retorna a resposta em JSON
        echo json_encode($alunos);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Erro ao buscar alunos: ' . $e->getMessage()]);
    }
} else {
    http_response_code(405); // Método não permitido
    echo json_encode(['error' => 'Método não permitido']);
}
