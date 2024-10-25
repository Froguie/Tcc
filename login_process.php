<?php
include("./backend/conexao.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $sql = "SELECT senha FROM Administrador WHERE email='$email'";
    $resultado = $conexao->query($sql);

    if ($resultado->num_rows > 0) {
        $row = $resultado->fetch_assoc();
        if ($senha === $row['senha']) {
            echo "Login realizado com sucesso.";
            // Redirecionar ou iniciar sessão aqui
        } else {
            echo "Senha incorreta.";
        }
    } else {
        echo "Email não encontrado.";
    }
}

$conexao->close();
?>
