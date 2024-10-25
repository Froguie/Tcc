<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <header class="navbar bg-red-400 p-4">
        <h1 class="text-white text-xl">Cadastro</h1>
    </header>

    <div class="flex justify-center items-center h-screen">
        <div class="bg-white p-6 rounded shadow-md w-1/3">
            <h2 class="text-2xl text-black font-bold mb-4">Cadastro</h2>
            <form action="cadastro_process.php" method="POST" class="space-y-4">
                <div>
                    <label for="nome" class="text-black block">Usuário:</label>
                    <input type="text" name="nome" id="nome" class="w-full border rounded p-2 text-black" required>
                </div>
                <div>
                    <label for="email" class="text-black block">Email:</label>
                    <input type="email" name="email" id="email" class="w-full border rounded p-2 text-black" required>
                </div>
                <div>
                    <label for="senha" class="text-black block">Senha:</label>
                    <input type="password" name="senha" id="senha" class="w-full border rounded p-2 text-black" required>
                </div>
                <button type="submit" class="bg-red-400 text-white px-4 py-2 rounded hover:bg-red-500 transition">Cadastrar</button>
            </form>
        </div>
    </div>
</body>

</html>
