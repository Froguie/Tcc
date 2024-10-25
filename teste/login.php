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
            header("Location: index.php"); // Redirect to index
            exit();
        } else {
            echo "Senha incorreta.";
        }
    } else {
        echo "Email não encontrado.";
    }

    $stmt->close();
    $conexao->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <header class="navbar">
        <h1>Login</h1>
    </header>

    <div class="flex justify-center items-center h-screen">
        <div class="bg-white p-6 rounded shadow-md w-1/3">
            <h2 class="text-2xl text-black font-bold mb-4">Login</h2>
            <form action="login.php" method="POST" class="space-y-4">
                <div>
                    <label for="email" class="text-black block">Email:</label>
                    <input type="email" name="email" id="email" class="w-full text-black border rounded p-2" required>
                </div>
                <div>
                    <label for="senha" class="text-black block">Senha:</label>
                    <input type="password" name="senha" id="senha" class="w-full text-black border rounded p-2" required>
                </div>
                <button type="submit" class="bg-red-400 text-white px-4 py-2 rounded">Login</button>
            </form>
        </div>
    </div>
</body>
</html>
