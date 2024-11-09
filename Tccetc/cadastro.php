<?php
include("./backend/conexao.php");
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
    $tipoConta = $_POST['tipo_conta'];

    // Use prepared statement
    $stmt = $conexao->prepare("INSERT INTO usuario (nome, email, senha, tipoConta) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nome, $email, $senha, $tipoConta);

    if ($stmt->execute()) {
        // Redirect to login page 
        header("Location: login.php?success=1");
        exit();
    } else {
        echo "Erro ao cadastrar: " . $stmt->error;
    }
    $stmt->close();
    $conexao->close();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-orange-300 flex items-center justify-center h-screen">
    <div class="bg-black p-8 rounded-lg shadow-lg w-1/3">
        <h2 class="text-3xl text-white font-bold text-center mb-6">Cadastro</h2>
        <form action="cadastro.php" method="POST" class="space-y-5">
            <div>
                <label for="nome" class="text-white block mb-1">Usuário:</label>
                <input placeholder="João Victor" type="text" name="nome" id="nome"
                    class="w-full border rounded p-2 text-black" required>
            </div>
            <div>
                <label for="email" class="text-white block mb-1">Email:</label>
                <input placeholder="exemplo@gmail.com" type="email" name="email" id="email"
                    class="w-full border rounded p-2 text-black" required>
            </div>
            <div>
                <label for="senha" class="text-white block mb-1">Senha:</label>
                <input placeholder="********" type="password" name="senha" id="senha"
                    class="w-full border rounded p-2 text-black" required>
            </div>
            <div>
                <label class="text-white block mb-1">Categoria:</label>
                <select name="tipo_conta" class="mt-1 w-full px-4 py-2 border rounded" required>
                    <option value="mesa">Mesa</option>
                    <option value="administrador">Administrador</option>
                    <option value="funcionario">Funcionário</option>
                    <option value="cozinha">Cozinha</option>
                </select>

            </div>
            <div class="flex justify-center mt-4">
                <button type="submit"
                    class="bg-orange-300 text-black px-4 py-2 w-full rounded transition-transform transform hover:scale-105 hover:bg-orange-400">Cadastrar</button>
            </div>
            <div class="flex justify-center">
                <p class="text-white text-center">Já tem cadastro? <a href="login.php"
                        class="text-orange-300 hover:underline">Entre</a></p>
            </div>
        </form>
    </div>
</body>

</html>