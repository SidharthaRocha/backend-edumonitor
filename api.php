<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $input = json_decode(file_get_contents('php://input'), true);

        if (empty($input['titulo']) || empty($input['descricao'])) {
            throw new Exception('Os campos "título" e "descrição" são obrigatórios.');
        }

        $notificacao = [
            'id' => uniqid(),
            'titulo' => $input['titulo'],
            'descricao' => $input['descricao'],
            'lida' => false,
        ];

        // Opcional: Salvar no banco de dados.
        // $pdo = new PDO('mysql:host=localhost;dbname=sua_base', 'usuario', 'senha');
        // $stmt = $pdo->prepare("INSERT INTO notificacoes (titulo, descricao) VALUES (?, ?)");
        // $stmt->execute([$input['titulo'], $input['descricao']]);

        echo json_encode($notificacao);
    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Método não permitido']);
}

?>
