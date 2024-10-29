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

<body class="bg-orange-300 flex flex-col h-screen">

    <nav class="bg-black border-gray-200">
        <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
            <a class="flex items-center space-x-3 rtl:space-x-reverse">
                <img src="../imagens/iconepessoa.png" class="rounded-full h-8 w-9" alt="Ícone da conta" />
                <span class="self-center text-2xl font-semibold text-white whitespace-nowrap">
                    <?php echo htmlspecialchars($_SESSION['nome']); ?>
                </span>
            </a>
            <button data-collapse-toggle="navbar-default" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200" aria-controls="navbar-default" aria-expanded="false">
                <span class="sr-only">Abrir menu principal</span>
                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15" />
                </svg>
            </button>
            <div class="hidden w-full md:block md:w-auto" id="navbar-default">
                <ul class="font-medium bg-black flex flex-col p-4 md:p-0 mt-4 border border-gray-100 rounded-lg md:flex-row md:space-x-8 rtl:space-x-reverse md:mt-0 md:border-0">
                    <li>
                        <a href="caixa.php" class="block py-2 px-3 text-white rounded md:p-0 hover:text-gray-300">Caixa</a>
                    </li>
                    <li>
                        <a href="telaMesas.php" class="block py-2 px-3 text-white rounded md:p-0 hover:text-gray-300">Mesas</a>
                    </li>
                    <li>
                        <a href="produtos.php" class="block py-2 px-3 text-white rounded md:p-0 hover:text-gray-300">Produtos</a>
                    </li>
                    <li>
                        <a href="logout.php" class="block py-2 px-3 text-white rounded md:p-0 hover:text-orange-300">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="flex-grow flex items-center justify-center flex-col text-center">
        <h1 class="mb-4 text-4xl font-extrabold leading-none tracking-tight text-black md:text-5xl lg:text-6xl">Bem-vindo ao Tasty</h1>

        <?php if (isset($_SESSION['nome'])): ?>
            <h3 class="text-xl">Olá, <?php echo htmlspecialchars($_SESSION['nome']); ?></h3>
        <?php else: ?>
            <p class="mb-6 text-lg font-normal text-black lg:text-xl sm:px-16 xl:px-48">
                Aqui no Tasty, focamos em mercados onde tecnologia, inovação e capital podem desbloquear valor a longo prazo e impulsionar o crescimento econômico.
            </p>
        <?php endif; ?>
</main>

</body>

</html>