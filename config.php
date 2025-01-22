<?php
$servername = getenv('MYSQLHOST');  // Exemplo: junction.proxy.rlwy.net
$username = getenv('MYSQLUSER');    // Exemplo: root
$password = getenv('MYSQLPASSWORD'); // A senha fornecida
$dbname = getenv('MYSQLDATABASE');  // O nome do banco de dados

try {
    // Cria a conexão com o banco de dados
    $conn = new PDO("mysql:host=$servername;port=58308;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Conectado com sucesso!";
} catch (PDOException $e) {
    echo "Conexão falhou: " . $e->getMessage();
}


?>
