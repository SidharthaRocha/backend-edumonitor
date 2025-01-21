<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

include 'config.php'; // Arquivo de configuração do banco de dados

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $stmt = $pdo->query('SELECT id, event_name, event_date, reminder, created_at FROM calendario');
        $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($events) {
            echo json_encode([
                'status' => 'success',
                'data' => ['events' => $events]
            ]);
        } else {
            echo json_encode([
                'status' => 'success',
                'data' => ['events' => []],
                'message' => 'Nenhum evento encontrado.'
            ]);
        }
    } catch (PDOException $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Erro ao recuperar eventos: ' . $e->getMessage()
        ]);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $eventName = $input['event_name'] ?? '';
    $eventDate = $input['event_date'] ?? '';
    $reminder = $input['reminder'] ?? 0;

    if ($eventName && $eventDate) {
        try {
            $stmt = $pdo->prepare('INSERT INTO calendario (event_name, event_date, reminder, created_at) VALUES (?, ?, ?, NOW())');
            $stmt->execute([$eventName, $eventDate, $reminder]);
            echo json_encode(['status' => 'success', 'message' => 'Evento adicionado com sucesso!']);
        } catch (PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => 'Erro ao adicionar evento: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Nome do evento e data são obrigatórios.']);
    }
}
?>
