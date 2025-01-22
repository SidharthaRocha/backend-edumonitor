<?php
require __DIR__ . '/vendor/autoload.php';  // Carrega as bibliotecas do Composer

// Carrega o arquivo .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Obter as variáveis de ambiente
$servername = $_ENV['MYSQLHOST'];   // Host do banco de dados
$username = $_ENV['MYSQLUSER'];     // Usuário do banco de dados
$password = $_ENV['MYSQLPASSWORD']; // Senha do banco de dados
$dbname = $_ENV['MYSQLDATABASE'];   // Nome do banco de dados

try {
    // Criar a conexão com PDO
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Conectado com sucesso!";
} catch (PDOException $e) {
    echo "Conexão falhou: " . $e->getMessage();
}
?>
