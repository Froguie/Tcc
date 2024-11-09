<?php
// Inicia a sessão se não estiver iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Destrói todas as variáveis da sessão
session_unset();  // Limpa as variáveis da sessão

// Destrói a sessão
session_destroy();

// Redireciona o usuário para a página de login
header("Location: index.php");
exit; // Garante que o script pare após o redirecionamento
?>
