<?php
if (!isset($_SESSION)) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Início</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-orange-300 select-none flex flex-col h-screen">
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
                <a href="caixa.php" class="text-white hover:text-black hover:underline transition hover:bg-orange-300 px-3 md:px-4 py-2 rounded-md">Caixa</a>
                <a href="telaMesas.php" class="text-white hover:text-black hover:underline transition hover:bg-orange-300 px-3 md:px-4 py-2 rounded-md">Mesas</a>
                <a href="produtos.php" class="text-white hover:text-black hover:underline transition hover:bg-orange-300 px-3 md:px-4 py-2 rounded-md">Produto</a>

                <!-- Botão de Logout -->
                <a href="../logout.php" class="text-white bg-red-600 hover:bg-red-700 px-3 md:px-4 py-2 rounded-md">
                    Sair
                </a>
            </div>
        </div>
    </nav>

    <!--CONTEUDO-->
    <main class="flex-grow flex items-center justify-center flex-col text-center min-h-[500px] p-4">
        <img src="../imagens/logo.png" alt="logo tasty" class="h-32 sm:h-40 md:h-48 lg:h-56 mb-4 md:mb-8">
        <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl leading-none tracking-tight text-black mb-2 font-bold">
            TASTY X
        </h1>
        <h1
            class="text-xl sm:text-2xl hover:cursor-pointer select-none md:text-3xl px-4 py-2 rounded transition-transform transform hover:scale-105 mb-2">
            Sua cozinha, nosso controle.
        </h1>
    </main>

</body>

</html>