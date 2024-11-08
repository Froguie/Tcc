<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Início</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="../path/to/flowbite/dist/flowbite.min.js"></script>
</head>

<body class="bg-orange-300 flex flex-col h-vh overflow-hidden">
<div>

</div>

    <main class="flex-grow flex items-center justify-center flex-col text-center" style="height: 700px;">
      <img src="../imagens/cu.png" alt="logo tasty" class="h-2/4 mb-8">
        <h1 class="text-4xl font-extrabold leading-none tracking-tight text-black md:text-5xl lg:text-6xl mb-2">Bem-Vindo Tasty X</h1>
        <a href="login.php" class="bg-orange-300 text-black px-6 py-2  rounded transition-transform transform hover:scale-105 hover:bg-orange-400 hover:no-underline mb-2">Logar</a>
<a href="cadastro.php" class="bg-orange-300 text-black px-6 py-2  rounded transition-transform transform hover:scale-105 hover:bg-orange-400 hover:no-underline">Cadastrar</a>
    </main>

</body>

</html>
