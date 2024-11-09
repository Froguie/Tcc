<?php
include 'data.php'; // Carrega as mesas e pedidos
session_start();
if (!isset($_SESSION['nome']))
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tela de Mesas</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-orange-300">
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

    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-6">Caixa</h1>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <?php foreach ($mesas as $mesa): ?>
                <div class="bg-black shadow-md rounded-lg p-8 border-gray-200">
                    <h2 class="text-xl text-white font-semibold">Mesa <?= $mesa["numero"] ?></h2>

                    <?php if (!empty($mesa["pedido"])): ?>
                        <div class="mt-4">
                            <h3 class="text-white font-semibold mb-2">Pedidos:</h3>
                            <ul class="list-disc pl-5">
                                <?php foreach ($mesa["pedido"] as $pedido): ?>
                                    <li><?= $pedido["quantidade"] ?>x <?= $pedido["item"] ?> -
                                        R$<?= number_format($pedido["preco"], 2, ',', '.') ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php else: ?>
                        <p class="text-white text-sm mt-4">Nenhum pedido registrado.</p>
                    <?php endif; ?>

                    <div class="mt-4">
                        <a href="mesa.php?numero=<?= $mesa['numero'] ?>"
                            class="bg-orange-300 text-black px-4 py-2 rounded-md hover:bg-orange-400">Gerenciar</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>

</html>