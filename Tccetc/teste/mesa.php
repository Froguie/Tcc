<?php
include 'data.php';

$numeroMesa = $_GET['numero'] ?? null;
$mesaSelecionada = $mesas[$numeroMesa - 1] ?? null;

if (!$mesaSelecionada) {
    echo "Mesa não encontrada!";
    exit();
}

session_start();
if (!isset($_SESSION['nome'])) {
    $_SESSION['nome'] = 'Visitante'; // Nome padrão se não houver sessão.
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
        <h1 class="text-3xl font-bold">Mesa <?= htmlspecialchars($mesaSelecionada["numero"]); ?></h1>
        <p>Status: <span class="<?= $mesaSelecionada["status"] == 'Ocupada' ? 'text-red-600' : 'text-green-600' ?>">
                <?= htmlspecialchars($mesaSelecionada["status"]); ?>
            </span></p>

        <div class="mt-4">
            <h2 class="font-semibold">Pedidos:</h2>
            <ul class="list-disc pl-5">
                <!-- Verifica se a chave 'pedido' existe e se é um array -->
                <?php if (isset($mesaSelecionada["pedido"]) && is_array($mesaSelecionada["pedido"])): ?>
                    <?php foreach ($mesaSelecionada["pedido"] as $pedido): ?>
                        <li>
                            <?= htmlspecialchars($pedido["quantidade"]); ?>x
                            <?= htmlspecialchars($pedido["item"]); ?> -
                            R$<?= number_format($pedido["preco"], 2, ',', '.'); ?>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li>Nenhum pedido encontrado.</li>
                <?php endif; ?>
            </ul>
        </div>

        <div class="mt-6">
            <a href="adicionarPedido.php?numero=<?= $mesaSelecionada['numero']; ?>"
                class="bg-black hover:bg-green-600 text-white px-4 py-2 rounded-md">Adicionar Pedido</a>
            <a href="atualizarStatus.php?numero=<?= $mesaSelecionada['numero']; ?>&status=Livre"
                class="bg-black hover:bg-red-600 text-white px-4 py-2 rounded-md">Finalizar Mesa</a>
        </div>
    </div>
</body>

</html>