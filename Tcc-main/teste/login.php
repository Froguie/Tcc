<?php
include("../backend/conexao.php");
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Use prepared statement
    $stmt = $conexao->prepare("SELECT nome, senha FROM usuario WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($nome, $hashedPassword);
        $stmt->fetch();

        if (password_verify($senha, $hashedPassword)) {
            $_SESSION['nome'] = $nome; // Store user name in session
            header("Location: admin.php"); // Redirect to account
            exit();
        } else {
            echo "<script>alert('Senha incorreta.');</script>";
        }
    } else {
        echo "<script>alert('Email não encontrado.');</script>";
    }

    $stmt->close();
    $conexao->close();
}

if (isset($_SESSION['user'])) {
    switch ($_SESSION['user']['tipo']) {
        case 'mesa':
            header('Location: mesa.php');
            exit();
        case 'funcionario':
            header('Location: funcionario.php');
            exit();
        case 'administrador':
            header('Location: administrador.php');
            exit();
    }
}

if (isset($_SESSION['mensagem_sucesso'])) {
    echo "<script>alert('" . $_SESSION['mensagem_sucesso'] . "');</script>";
    unset($_SESSION['mensagem_sucesso']);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-orange-300 flex items-center justify-center h-screen flex-col">
    <div class="bg-black p-8 rounded-lg shadow-lg w-11/12 sm:w-1/3">
        <h2 class="text-3xl text-white font-bold text-center mb-6">Login</h2>
        <form action="login.php" method="POST" class="space-y-5">
            <div>
                <label for="email" class="text-white block mb-1">Email:</label>
                <input placeholder="exemplo@gmail.com" type="email" name="email" id="email" class="w-full border rounded p-2 text-black" required>
            </div>
            <div>
                <label for="senha" class="text-white block mb-1">Senha:</label>
                <input placeholder="******" type="password" name="senha" id="senha" class="w-full border rounded p-2 text-black" required>
            </div>
            <div class="flex justify-center">
                <button type="submit" class="bg-orange-300 text-black px-4 py-2 rounded transition-transform transform hover:scale-105 w-full hover:bg-orange-400">Login</button>
            </div>
            <div class="text-center text-white">
                <p>Não tem cadastro? <a href="cadastro.php" class="text-orange-300 hover:underline">Crie uma conta</a></p>
            </div>
        </form>
    </div>
    <button class="rounded-md text-gray-800 hover:text-gray-900 transition-all hover:scale-105 mt-4 w-11/12 sm:w-1/3" onclick="window.location.href='index.php'">Voltar para Página Inicial</button>
</body>

</html>
