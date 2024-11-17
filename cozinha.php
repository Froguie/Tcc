<?php
include 'data.php'; // Carrega as funções para acessar as mesas e pedidos
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php'); // Redireciona para login se não estiver logado
    exit();
}


function getMesasComPedidos()
{
    global $conexao;

    // Consulta SQL
    $sql = "SELECT DISTINCT m.numero AS numeroMesa, m.statusMesa, 
                   JSON_ARRAYAGG(
                       JSON_OBJECT(
                           'nome', p.nomeProduto,
                           'preco', p.precoProduto,
                           'quantidade', pe.quantidade
                       )
                   ) AS pedidos
            FROM mesa m
            JOIN pedido pe ON m.numero = pe.numeroMesa
            JOIN produto p ON pe.codProPe = p.codProduto
            WHERE pe.statusPedido = 'aberto'
            GROUP BY m.numero, m.statusMesa";

    // Executa a consulta
    $result = $conexao->query($sql);

    // Verifica se houve erro na consulta
    if (!$result) {
        die("Erro na consulta SQL: " . $conexao->error);
    }

    // Processa os resultados
    $mesas = [];
    while ($row = $result->fetch_assoc()) {
        $row['pedidos'] = json_decode($row['pedidos'], true); // Decodifica os pedidos
        $mesas[] = $row;
    }

    return $mesas;
}
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

// Função para finalizar o pedido da mesa
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["finalizarMesa"])) {
    $mesaFinalizada = $_POST["finalizarMesa"];
    $sql = "UPDATE pedido SET statusPedido = 'finalizado' WHERE numeroMesa = ? AND statusPedido = 'aberto'";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $mesaFinalizada);
    $stmt->execute();
    header("Location: " . $_SERVER["PHP_SELF"]);
    exit();
}

// Carrega as mesas com pedidos abertos
$mesas = getMesasComPedidos();

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
            <div id="menu"
                class="w-full md:flex md:w-auto flex-col md:flex-row items-center md:space-x-4 mt-4 md:mt-0">
                <a href="#"
                    class="text-orange-300 underline hover:text-black hover:underline transition hover:bg-orange-300 px-3 md:px-4 py-2 rounded-md">Caixa</a>
                <a href="telaMesas.php"
                    class="text-white hover:text-black hover:underline transition hover:bg-orange-300 px-3 md:px-4 py-2 rounded-md">Mesas</a>
                <a href="produtos.php"
                    class="text-white hover:text-black hover:underline transition hover:bg-orange-300 px-3 md:px-4 py-2 rounded-md">Produto</a>
                <a href="../logout.php" class="text-white bg-red-600 hover:bg-red-700 px-3 md:px-4 py-2 rounded-md">
                    Sair
                </a>
            </div>
        </div>
    </nav>

    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-6">Cozinha</h1>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <?php if (!empty($mesas)): ?>
                <?php foreach ($mesas as $mesa): ?>
                    <div class="bg-black shadow-md rounded-lg p-8 border-gray-200">
                        <h2 class="text-xl text-white font-semibold">Mesa <?= htmlspecialchars($mesa["numeroMesa"]); ?></h2>
                        <p class="text-green-600">Status: <?= htmlspecialchars($mesa["status"] ?? "Indefinido"); ?></p>

                        <div class="mt-4">
                            <h3 class="text-white font-semibold mb-2">Pedidos:</h3>
                            <ul class="list-disc pl-5">
                                <?php
                                list($totalMesa, $totalProdutos) = calcularTotalPedido($mesa["pedidos"]);
                                foreach ($mesa["pedidos"] as $pedido): ?>
                                    <li class="text-white"><?= htmlspecialchars($pedido["quantidade"]) ?>
                                        <?= htmlspecialchars($pedido["nome"]) ?> -
                                        R$<?= number_format($pedido["preco"], 2, ',', '.') ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                            <p class="text-white font-bold mt-2">Total de produtos: <?= $totalProdutos ?></p>
                            <p class="text-white font-bold mt-2">Total: R$<?= number_format($totalMesa, 2, ',', '.') ?></p>
                        </div>

                        <div class="mt-4">
                            <form method="POST">
                                <button type="submit" name="finalizarMesa" value="<?= $mesa['numeroMesa'] ?>"
                                    class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">
                                    Pedido Finalizado
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-white text-center">Nenhuma mesa com pedidos abertos.</p>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>
