<?php
// Habilita CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Define o tipo de conteúdo da resposta
header('Content-Type: application/json');

// Conexão com o banco de dados
include 'config.php';

// Consulta para pegar os eventos
$stmt = $pdo->query("SELECT * FROM events");
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Retorna os eventos em formato JSON
echo json_encode($events);
?>
