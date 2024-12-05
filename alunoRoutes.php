<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204); // Retorna uma resposta sem conteúdo (204 No Content)
    exit;
}

include_once 'AlunoController.php';  // Inclui o controller

$alunoController = new AlunoController();

// Rota para pegar os dados de um aluno
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $alunoController->getAluno($_GET['id']);
}

// Rota para atualizar os dados do aluno
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];
    $desempenho = $_POST['desempenho'];
    $nota = $_POST['nota'];
    $observacao = $_POST['observacao'];

    $alunoController->updateAluno($id, $desempenho, $nota, $observacao);
}

// Rota para adicionar um novo aluno
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nome'])) {
    $nome = $_POST['nome'];
    $desempenho = $_POST['desempenho'];
    $nota = $_POST['nota'];
    $observacao = $_POST['observacao'];

    $alunoController->addAluno($nome, $desempenho, $nota, $observacao);
}
?>
