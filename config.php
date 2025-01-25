<?php
// Configuração do banco de dados remoto no Railway
$host = 'mysql.railway.internal'; // MYSQLHOST
$db = 'railway'; // MYSQLDATABASE
$user = 'root'; // MYSQLUSER
$pass = 'BHfvIHTDwRyggmmHaNeFyTjJTwSexvum'; // MYSQLPASSWORD
$port = 3306; // MYSQLPORT

try {
    // Conexão usando as configurações do Railway
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo 'Conexão bem-sucedida com o banco de dados!';
} catch (PDOException $e) {
    // Caso ocorra erro, retorna o status HTTP 500 e exibe a mensagem
    echo 'Conexão falhou: ' . $e->getMessage();
    http_response_code(500);
    exit;
}
?>

