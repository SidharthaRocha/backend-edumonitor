<?php
// Permitir CORS de uma origem específica (ajuste o URL de acordo com a URL do seu frontend)
header("Access-Control-Allow-Origin: *"); // Ajuste a URL conforme necessário
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Credentials: true"); // Permite o envio de cookies e credenciais

// Se o método for OPTIONS, retorna uma resposta vazia (pré-voo)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

// Caminho do arquivo JSON (simulando o banco de dados)
$recursosFile = 'recursos.json';

// Funções de leitura e escrita do arquivo JSON
function readData() {
    global $recursosFile;
    if (!file_exists($recursosFile)) {
        file_put_contents($recursosFile, json_encode([]));
    }
    return json_decode(file_get_contents($recursosFile), true);
}

function saveData($data) {
    global $recursosFile;
    file_put_contents($recursosFile, json_encode($data, JSON_PRETTY_PRINT));
}

// Processando a requisição POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true); // Lê os dados da requisição como JSON

    // Verifica se todos os campos obrigatórios estão presentes
    if (!isset($input['nome']) || !isset($input['tipo']) || !isset($input['link'])) {
        http_response_code(400); // Bad Request
        echo json_encode(["message" => "Todos os campos são obrigatórios."]);
        exit;
    }

    // Cria um novo recurso com ID único
    $novoRecurso = [
        "id" => uniqid(),
        "nome" => $input['nome'],
        "tipo" => $input['tipo'],
        "link" => $input['link'],
    ];

    // Lê os recursos existentes
    $recursos = readData();
    $recursos[] = $novoRecurso; // Adiciona o novo recurso
    saveData($recursos); // Salva os recursos no arquivo

    // Responde com sucesso
    http_response_code(201); // Created
    echo json_encode(["message" => "Recurso adicionado com sucesso.", "recurso" => $novoRecurso]);
    exit;
}

// Processando a requisição GET para retornar os recursos
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $recursos = readData(); // Lê os dados do arquivo JSON
    echo json_encode($recursos); // Retorna os recursos como JSON
    exit;
}

// Se o método não for POST ou GET, retorna um erro 405
http_response_code(405); // Method Not Allowed
echo json_encode(["message" => "Método não permitido."]);
exit;
