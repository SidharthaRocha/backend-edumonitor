<?php
$servername = "junction.proxy.rlwy.net"; // Host do banco de dados
$username = "root";                      // Usuário do banco de dados
$password = "BHfvIHTDwRyggmmHaNeFyTjJTwSexvum"; // Senha do banco de dados
$dbname = "railway";                     // Nome do banco de dados

try {
    // Criar a conexão com PDO
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);

    // Configurar o modo de erro do PDO para exceções
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Se a conexão for bem-sucedida
    echo "Conectado com sucesso!";
} catch (PDOException $e) {
    // Se houver erro na conexão
    echo "Conexão falhou: " . $e->getMessage();
}
?>
