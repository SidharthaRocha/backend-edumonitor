<?php
session_start(); // Inicia a sessão

// Destrói a sessão para deslogar o usuário
session_unset(); // Libera todas as variáveis de sessão
session_destroy(); // Destroi a sessão

// Redireciona para a página de login
header('Location: login.php');
exit();
?>
