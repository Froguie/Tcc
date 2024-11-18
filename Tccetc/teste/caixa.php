<?php
include 'data.php'; // Inclui a função para carregar as mesas e pedidos
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}

// Conexão com o banco de dados
include('../backend/conexao.php');

// Função para carregar as mesas com o status atualizado
function getmesa()
{
    global $conexao;
    $mesas = [];
    $result = $conexao->query("SELECT * FROM mesa"); // Obtém todas as mesas com seu status
    while ($row = $result->fetch_assoc()) {
        $mesas[] = $row;
    }
    return $mesas;
}


// Carrega as mesas do banco de dados
$mesas = getmesa();

// Função para calcular o valor total de um pedido
function calcularTotalPedido($pedido)
{
    $total = 0;
    $quantidadeProdutos = 0;
    foreach ($pedido as $item) {
        $quantidade = isset($item["quantidade"]) ? $item["quantidade"] : 0;
        $preco = isset($item["preco"]) ? $item["preco"] : 0;
        $total += $quantidade * $preco;
        $quantidadeProdutos += $quantidade;
    }
    return [$total, $quantidadeProdutos];
}
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
    <nav class="bg-black border-gray-200">
        <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
            <a class="flex items-center space-x-3">
                <div class="rounded-full bg-orange-300 h-8 w-8"></div>
                <span class="self-center text-xl md:text-2xl font-semibold text-white whitespace-nowrap">
                    Olá,
                    <?php echo $_SESSION['usuario']['nome']; ?>
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
        <h1 class="text-3xl font-bold mb-6">Caixa</h1>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <?php foreach ($mesas as $mesa): ?>
                <div class="bg-black shadow-md rounded-lg p-8 border-gray-200">
                    <h2 class="text-xl text-white font-semibold">Mesa <?= htmlspecialchars($mesa["numero"]); ?></h2>
                    <p class="transition duration-300 ease-in-out 
    <?php echo $mesa['statusMesa'] == 'ocupada' ? 'text-red-600' : 'text-green-600'; ?>">
    Status: <?= htmlspecialchars($mesa["statusMesa"]); ?>
                    </p>

                    <?php if (!empty($mesa["pedido"])): ?>
                        <div class="mt-4">
                            <h3 class="text-white font-semibold mb-2">Pedidos:</h3>
                            <ul class="list-disc pl-5">
                                <?php
                                list($totalMesa, $totalProdutos) = calcularTotalPedido($mesa["pedido"]);
                                foreach ($mesa["pedido"] as $pedido): ?>
                                    <li class="text-white"><?= htmlspecialchars($pedido["quantidade"]) ?> <?= htmlspecialchars($pedido["item"]) ?> - R$<?= number_format($pedido["preco"], 2, ',', '.') ?></li>
                                <?php endforeach; ?>
                            </ul>
                            <p class="text-white font-bold mt-2">Total de produtos: <?= $totalProdutos ?></p>
                            <p class="text-white font-bold mt-2">Total: R$<?= number_format($totalMesa, 2, ',', '.') ?></p>
                        </div>
                    <?php else: ?>
                        <p class="text-white text-sm mt-4">Nenhum pedido registrado.</p>
                    <?php endif; ?>

                    <div class="mt-4">
                        <a href="mesa.php?numero=<?= $mesa['numero'] ?>" class="bg-orange-300 text-black px-4 py-2 rounded-md hover:bg-orange-400">Gerenciar</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
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
