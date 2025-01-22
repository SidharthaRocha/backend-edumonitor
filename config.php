<?php
// Obter as variáveis de ambiente
$servername = getenv('MYSQLHOST');   // Host do banco de dados
$username = getenv('MYSQLUSER');     // Usuário do banco de dados
$password = getenv('MYSQLPASSWORD'); // Senha do banco de dados
$dbname = getenv('MYSQLDATABASE');   // Nome do banco de dados

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
