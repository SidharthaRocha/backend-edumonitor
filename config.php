<?php
// Conexão remota com o banco de dados utilizando as variáveis de ambiente
$host = getenv('MYSQLHOST');
$port = getenv('MYSQLPORT');
$db = getenv('MYSQLDATABASE');
$user = getenv('MYSQLUSER');
$password = getenv('MYSQLPASSWORD');

// Conexão com o banco de dados MySQL usando mysqli
$conn = new mysqli($host, $user, $password, $db, $port);

// Verifica se houve erro na conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
} else {
    echo "Conexão bem-sucedida!";
}

// Fechar a conexão
$conn->close();
?>
