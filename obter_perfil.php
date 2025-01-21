<?php
// Incluir o arquivo de configuração do banco de dados
include('config.php');

// Obter o ID do usuário da URL
$id = $_GET['id'] ?? null;

if ($id) {
    // Preparar a query de seleção
    $sql = "SELECT * FROM usuarios WHERE id = ?";

    // Usar prepared statement para evitar SQL Injection
    if ($stmt = $conn->prepare($sql)) {
        // Bind do parâmetro
        $stmt->bind_param("i", $id);

        // Executar a query
        $stmt->execute();

        // Obter o resultado
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Retornar os dados do usuário
            $user = $result->fetch_assoc();
            echo json_encode($user);
        } else {
            echo json_encode(['message' => 'Usuário não encontrado.']);
        }

        // Fechar o statement
        $stmt->close();
    } else {
        echo json_encode(['message' => 'Erro ao preparar a query.']);
    }
} else {
    echo json_encode(['message' => 'ID do usuário não fornecido.']);
}

// Fechar a conexão
$conn->close();
?>
