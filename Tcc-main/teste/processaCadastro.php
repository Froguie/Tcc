<?php
session_start();
require 'conexao.php'; // Conexão com o banco de dados

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
    $tipo = $_POST['tipo'];

    try {
        // Verifica se o email já está cadastrado
        $query = $conn->prepare("SELECT * FROM usuarios WHERE email = :email");
        $query->bindParam(':email', $email);
        $query->execute();

        if ($query->rowCount() > 0) {
            // Email já cadastrado
            $_SESSION['erro_cadastro'] = "Este email já foi cadastrado!";
            header('Location: cadastro.php');
            exit();
        }
    
        $query = $conn->prepare("INSERT INTO usuarios (nome, email, senha, tipo) VALUES (:nome, :email, :senha, :tipo)");
        $query->bindParam(':nome', $nome);
        $query->bindParam(':email', $email);
        $query->bindParam(':senha', $senha);
        $query->bindParam(':tipo', $tipo);

        $query->execute();

        // Define a mensagem de sucesso
        $_SESSION['mensagem_sucesso'] = "Cadastro realizado com sucesso!";
        header('Location: login.php');
        exit();
    } catch (PDOException $e) {
        echo "Erro ao cadastrar: " . $e->getMessage();
    }
}
?>
