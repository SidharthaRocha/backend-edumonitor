<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Simulação de recebimento de dados
$data = json_decode(file_get_contents('php://input'), true);

// Verificar se os campos necessários estão presentes
if (isset($data['feedback']) && isset($data['sugestao']) && isset($data['emailDestino'])) {
    // Simulação de sucesso no envio
    echo json_encode([
        'status' => 'success',
        'message' => 'Feedback enviado com sucesso!'
    ]);
} else {
    // Caso falhe, retornar um erro
    echo json_encode([
        'status' => 'error',
        'message' => 'Campos incompletos.'
    ]);
}
?>
