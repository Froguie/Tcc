<?php
include 'data.php'; // Inclui a função para carregar as mesas
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}

// Carrega as mesas do banco
$mesas = getMesas();

// Verifica se a variável 'numero' foi passada na URL
$numeroMesa = isset($_GET['numero']) ? intval($_GET['numero']) : null;
$mesaSelecionada = null;

// Procura pela mesa selecionada
foreach ($mesas as $mesa) {
    if ($mesa['numero'] == $numeroMesa) {
        $mesaSelecionada = $mesa;
        break;
    }
}

// Redireciona se a mesa não for encontrada
if (!$mesaSelecionada) {
    header("Location: caixa.php");
    exit();
}

// Carrega o carrinho da sessão, caso exista
$pedidosMesa = isset($_SESSION['carrinhos'][$numeroMesa]) ? $_SESSION['carrinhos'][$numeroMesa] : [];

// Função para calcular o valor total de um pedido
function calcularTotalPedido($pedido)
{
    $total = 0;
    foreach ($pedido as $item) {
        $total += $item["quantidade"] * $item["preco"];
    }
    return $total;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Gerenciar Mesa <?= htmlspecialchars($mesaSelecionada["numero"]); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-orange-300">
    <nav class="bg-black border-gray-200">
        <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
            <a class="flex items-center space-x-3">
                <div class="rounded-full bg-orange-300 h-8 w-8"></div>
                <span class="self-center text-xl md:text-2xl font-semibold text-white whitespace-nowrap">
                    Olá, <?= $_SESSION['usuario']['nome']; ?>
                </span>
            </a>
            <button id="menu-toggle" class="block md:hidden text-white focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                </svg>
            </button>
            <div id="menu" class="hidden w-full md:flex md:w-auto flex-col md:flex-row items-center md:space-x-4 mt-4 md:mt-0">
                <a href="caixa.php" class="text-white hover:text-black hover:underline transition hover:bg-orange-300 px-3 md:px-4 py-2 rounded-md">Caixa</a>
                <a href="telaMesas.php" class="text-white hover:text-black hover:underline transition hover:bg-orange-300 px-3 md:px-4 py-2 rounded-md">Mesas</a>
                <a href="produtos.php" class="text-white hover:text-black hover:underline transition hover:bg-orange-300 px-3 md:px-4 py-2 rounded-md">Produto</a>
                <a href="logout.php" class="text-white bg-red-600 hover:bg-red-700 px-3 md:px-4 py-2 rounded-md">Sair</a>
            </div>
        </div>
    </nav>

    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-6">Gerenciar Mesa <?= htmlspecialchars($mesaSelecionada['numero']); ?></h1>

        <?php if (!empty($pedidosMesa)): ?>
            <div>
                <h2 class="text-xl font-semibold text-white">Pedidos da Mesa:</h2>
                <ul class="list-disc pl-5 text-white">
                    <?php
                    $totalMesa = calcularTotalPedido($pedidosMesa);
                    foreach ($pedidosMesa as $pedido): ?>
                        <li><?= htmlspecialchars($pedido['quantidade']) ?> <?= htmlspecialchars($pedido['item']) ?> - R$<?= number_format($pedido['preco'], 2, ',', '.') ?></li>
                    <?php endforeach; ?>
                </ul>
                <p class="text-white font-bold mt-4">Total: R$<?= number_format($totalMesa, 2, ',', '.') ?></p>
            </div>
        <?php else: ?>
            <p class="text-white">Nenhum pedido registrado para esta mesa.</p>
        <?php endif; ?>
    </div>

    <script>
        // Toggle menu
        const menuToggle = document.getElementById('menu-toggle');
        const menu = document.getElementById('menu');
        menuToggle.addEventListener('click', () => {
            menu.classList.toggle('hidden');
        });
    </script>
</body>

</html>
