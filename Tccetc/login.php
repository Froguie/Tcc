<?php
include("./backend/conexao.php");
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Preparando a consulta para evitar SQL Injection
    $stmt = $conexao->prepare("SELECT codUsuario, nome, senha, tipoConta FROM usuario WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $nome, $hashedPassword, $tipoConta);
        $stmt->fetch();

        if (password_verify($senha, $hashedPassword)) {
            // Armazenando os dados do usuário na sessão
            $_SESSION['usuario'] = [
                'codUsuario' => $id,
                'nome' => $nome,
                'tipoConta' => $tipoConta
            ];

            // Copiar dados para a tabela correspondente, se necessário
            switch ($tipoConta) {
                case 'mesa':
                    $insertMesa = $conexao->prepare("INSERT IGNORE INTO mesa (codMesa, nomeMesa) VALUES (?, ?)");
                    $insertMesa->bind_param("is", $id, $nome);
                    $insertMesa->execute();
                    header('Location: cardapio.php');
                    break;
                
                case 'funcionario':
                    $insertFuncionario = $conexao->prepare("INSERT IGNORE INTO funcionario (codFuncionario, nomeFuncionario) VALUES (?, ?)");
                    $insertFuncionario->bind_param("is", $id, $nome);
                    $insertFuncionario->execute();
                    header('Location: funcionario.php');
                    break;
                
                case 'administrador':
                    $insertAdmin = $conexao->prepare("INSERT IGNORE INTO administrador (codAdmin, nomeAdministrador) VALUES (?, ?)");
                    $insertAdmin->bind_param("is", $id, $nome);
                    $insertAdmin->execute();
                    header('Location: ./teste/admin.php');
                    break;
                
                case 'cozinha':
                    $insertCozinha = $conexao->prepare("INSERT IGNORE INTO cozinha (codCozinha, nomeCozinha) VALUES (?, ?)");
                    $insertCozinha->bind_param("is", $id, $nome);
                    $insertCozinha->execute();
                    header('Location: cozinha.php');
                    break;
            }
            $_SESSION['nome'] = $nome;
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

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-orange-300 flex items-center justify-center h-screen flex-col">
    <div class="bg-black p-8 rounded-lg shadow-lg w-11/12 sm:w-1/3">
        <h2 class="text-3xl text-white font-bold text-center mb-6">Login</h2>
        <form action="login.php" method="POST" class="space-y-5">
            <div>
                <label for="email" class="text-white block mb-1">Email:</label>
                <input type="email" name="email" id="email" placeholder="exemplo@gmail.com"
                    class="w-full border rounded p-2 text-black focus:outline-none focus:ring-2 focus:ring-orange-400"
                    required>
            </div>
            <div>
                <label for="senha" class="text-white block mb-1">Senha:</label>
                <input type="password" name="senha" id="senha" placeholder="******"
                    class="w-full border rounded p-2 text-black focus:outline-none focus:ring-2 focus:ring-orange-400"
                    required>
            </div>
            <div class="flex justify-center">
                <button type="submit"
                    class="bg-orange-300 text-black px-4 py-2 rounded transition-transform transform hover:scale-105 w-full hover:bg-orange-400">
                    Login
                </button>
            </div>
            <div class="text-center text-white">
                <p>Não tem cadastro? <a href="cadastro.php" class="text-orange-300 hover:underline">Crie uma conta</a></p>
            </div>
        </form>
    </div>
    <button class="rounded-md text-gray-800 hover:text-gray-900 transition-all hover:scale-105 mt-4 w-11/12 sm:w-1/3"
        onclick="window.location.href='index.php'">Voltar para Página Inicial</button>
</body>

</html>

