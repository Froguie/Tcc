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
    $_SESSION['nome'] ='Visitante'; // Nome padrão se não houver sessão.
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
            <a class="flex items-center space-x-3 rtl:space-x-reverse">
                <div class="rounded-full bg-orange-300 h-8 w-8"></div>
                <span class="self-center text-2xl font-semibold text-white whitespace-nowrap">
                     Olá, <?php echo $_SESSION['nome']; ?>
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
                        <a href="admin.php" class="block py-2 px-3 text-white rounded md:p-0 hover:text-orange-300">Voltar</a>
                    </li>
                </ul>
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
        <a href="adicionarPedido.php?numero=<?= $mesaSelecionada['numero']; ?>" class="bg-black hover:bg-green-600 text-white px-4 py-2 rounded-md">Adicionar Pedido</a>
        <a href="atualizarStatus.php?numero=<?= $mesaSelecionada['numero']; ?>&status=Livre" class="bg-black hover:bg-red-600 text-white px-4 py-2 rounded-md">Finalizar Mesa</a>
    </div>
</div>
</body>
</html>
