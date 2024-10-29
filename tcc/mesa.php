<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$numeroMesa = $_GET['numero'];
$mesa = $conn->query("SELECT * FROM mesas WHERE numero = '$numeroMesa'")->fetch_assoc();
$pedidos = $conn->query("SELECT * FROM pedidos WHERE mesa_id = '$numeroMesa'");
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Mesa <?= $mesa['numero'] ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-6">Gerenciar Mesa <?= $mesa['numero'] ?></h1>

        <p class="text-sm <?= $mesa["status"] == 'Ocupada' ? 'text-red-600' : 'text-green-600' ?>">
            Status: <?= $mesa["status"] ?>
        </p>

        <?php if ($pedidos->num_rows > 0): ?>
            <div class="mt-4">
                <h3 class="font-semibold mb-2">Pedidos:</h3>
                <ul class="list-disc pl-5">
                    <?php while ($pedido = $pedidos->fetch_assoc()): ?>
                        <li>
                            <?= $pedido["quantidade"] ?>x <?= $pedido["item"] ?> - R$<?= number_format($pedido["preco"], 2, ',', '.') ?>
                        </li>
                    <?php endwhile; ?>
                </ul>
            </div>

            <div class="mt-4">
                <a href="adicionar_pedido.php?numero=<?= $mesa['numero'] ?>" class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">
                    Adicionar Pedido
                </a>
                <a href="cancelar_pedido.php?numero=<?= $mesa['numero'] ?>" class="bg-red-500 text-white px-4 py-2 rounded
                <a href="cancelar_pedido.php?numero=<?= $mesa['numero'] ?>" class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600">
                    Cancelar Pedidos
                </a>
            </div>
        <?php else: ?>
            <p class="text-gray-500">Nenhum pedido feito para esta mesa.</p>
            <div class="mt-4">
                <a href="adicionar_pedido.php?numero=<?= $mesa['numero'] ?>" class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">
                    Adicionar Pedido
                </a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
