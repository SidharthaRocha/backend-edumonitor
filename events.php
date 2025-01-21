<?php
header('Content-Type: application/json');

// Permitir origens específicas
header('Access-Control-Allow-Origin: *'); // Substitua pelo URL correto do frontend

// Permitir métodos HTTP necessários
header('Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS');

// Permitir cabeçalhos necessários
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Responder a requisições OPTIONS para pré-voo
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Simulação do banco de dados
$events = [];
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    echo json_encode($events);
    exit;
}

if ($method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['title']) || !isset($data['date'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Campos obrigatórios não enviados.']);
        exit;
    }

    $newEvent = [
        'id' => uniqid(),
        'title' => $data['title'],
        'date' => $data['date'],
        'reminder' => $data['reminder'] ?? false,
    ];

    $events[] = $newEvent;
    echo json_encode($newEvent);
    exit;
}

if ($method === 'DELETE') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'ID do evento não enviado.']);
        exit;
    }

    $events = array_filter($events, fn($event) => $event['id'] !== $data['id']);
    echo json_encode(['message' => 'Evento removido com sucesso.']);
    exit;
}

http_response_code(405);
echo json_encode(['error' => 'Método não permitido.']);
