<?php
session_start();
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Produtos</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-orange-300 min-h-screen flex flex-col">
    <!--NAVBAR-->
    <nav class="bg-black border-gray-200">
        <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
            <a class="flex items-center space-x-3">
                <div class="rounded-full bg-orange-300 h-8 w-8"></div>
                <span class="self-center text-xl md:text-2xl font-semibold text-white whitespace-nowrap">
                    Olá,
                    <?php echo isset($_SESSION['usuario']) ? $_SESSION['usuario']['nome'] : 'Usuário desconhecido'; ?>
                </span>
            </a>

            <!-- Menu Burger -->
            <button id="menu-toggle" class="block md:hidden text-white focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7">
                    </path>
                </svg>
            </button>

            <!-- Links da Navbar -->
            <div id="menu"
                class="hidden w-full md:flex md:w-auto flex-col md:flex-row items-center md:space-x-4 mt-4 md:mt-0">
                <a href="caixa.php"
                    class="text-white hover:text-black hover:underline transition hover:bg-orange-300 px-3 md:px-4 py-2 rounded-md">Caixa</a>
                <a href="mesa.php"
                    class="text-white hover:text-black hover:underline transition hover:bg-orange-300 px-3 md:px-4 py-2 rounded-md">Mesas</a>
                <a href="produtos.php"
                    class="text-white hover:text-black hover:underline transition hover:bg-orange-300 px-3 md:px-4 py-2 rounded-md">Produto</a>

                <!-- Botão de Logout -->
                <a href="logout.php" class="text-white bg-red-600 hover:bg-red-700 px-3 md:px-4 py-2 rounded-md">
                    Sair
                </a>
            </div>
        </div>
    </nav>

    <!-- Conteúdo Principal -->
    <div class="p-6 flex-1">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">Todos</h2>

            <!-- Exibir o botão "Adicionar" apenas para administradores -->
            <?php if (isset($_SESSION['tipoConta']) && $_SESSION['tipoConta'] === 'administrador'): ?>
                <button class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">Adicionar</button>
            <?php endif; ?>
        </div>

        <!-- Categorias de Produtos -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Pratos -->
            <div class="bg-gray-200 p-4 rounded-lg shadow-md">
                <h3 class="text-lg font-semibold mb-2">Pratos</h3>
                <p>Macarrão ao sugo</p>
            </div>

            <!-- Sobremesas -->
            <div class="bg-gray-200 p-4 rounded-lg shadow-md">
                <h3 class="text-lg font-semibold mb-2">Sobremesas</h3>
                <p class="text-gray-500">Nenhum item</p>
            </div>

            <!-- Bebidas -->
            <div class="bg-gray-200 p-4 rounded-lg shadow-md">
                <h3 class="text-lg font-semibold mb-2">Bebidas</h3>
                <p class="text-gray-500">Nenhum item</p>
            </div>
        </div>
    </div>
</body>

</html>