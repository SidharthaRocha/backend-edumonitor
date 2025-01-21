<?php
header('Content-Type: application/json');

// Adiciona os cabeçalhos CORS para permitir requisições de qualquer origem
header('Access-Control-Allow-Origin: *'); // Pode especificar um domínio específico no lugar de '*'
header('Access-Control-Allow-Methods: GET, POST, PUT'); // Métodos permitidos
header('Access-Control-Allow-Headers: Content-Type'); // Cabeçalhos permitidos

// Simula conexão com banco de dados (substitua por sua lógica)
$notificacoes = json_decode(file_get_contents('notificacoes.json'), true) ?? [];

// Método POST - Criar notificação
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    $titulo = $input['titulo'] ?? '';
    $mensagem = $input['mensagem'] ?? '';
    $destinatarios = $input['destinatarios'] ?? [];

    if (empty($titulo) || empty($mensagem) || empty($destinatarios)) {
        echo json_encode(['error' => 'Todos os campos são obrigatórios.']);
        http_response_code(400);
        exit;
    }

    $id = uniqid();
    $notificacoes[] = [
        'id' => $id,
        'titulo' => $titulo,
        'mensagem' => $mensagem,
        'destinatarios' => $destinatarios,
        'status' => 'Não Lida',
    ];

    file_put_contents('notificacoes.json', json_encode($notificacoes));

    echo json_encode(['message' => 'Notificação enviada com sucesso!']);
    exit;
}

// Método GET - Listar notificações
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo json_encode($notificacoes);
    exit;
}

// Método PUT - Marcar como lida
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    parse_str($_SERVER['QUERY_STRING'], $query);
    $id = $query['id'] ?? '';

    foreach ($notificacoes as &$notificacao) {
        if ($notificacao['id'] === $id) {
            $notificacao['status'] = 'Lida';
            file_put_contents('notificacoes.json', json_encode($notificacoes));
            echo json_encode(['message' => 'Notificação marcada como lida.']);
            exit;
        }
    }

    echo json_encode(['error' => 'Notificação não encontrada.']);
    http_response_code(404);
    exit;
}
?>
