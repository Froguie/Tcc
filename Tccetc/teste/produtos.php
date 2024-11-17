<?php
session_start();
include("../backend/conexao.php");

// Função para obter os produtos por categoria
function getProdutosPorCategoria($categoria)
{
    global $conexao; // Acesso à conexão do banco de dados
    $query = "SELECT * FROM produto WHERE categoriaProduto = ?";
    $stmt = $conexao->prepare($query);
    $stmt->bind_param("s", $categoria); // "s" para string
    $stmt->execute();
    $result = $stmt->get_result();

    // Verificação para erro na consulta SQL
    if ($stmt->error) {
        echo "Erro na consulta: " . $stmt->error;
    }

    $produtos = [];
    while ($row = $result->fetch_assoc()) {
        $produtos[] = $row;
    }

    return $produtos;
}

// Buscar produtos por categoria
$produtosPratos = getProdutosPorCategoria('Prato');
$produtosSobremesas = getProdutosPorCategoria('Sobremesa');
$produtosBebidas = getProdutosPorCategoria('Bebida');
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Produtos</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-orange-300 select-none min-h-screen flex flex-col">
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
                <a href="telaMesas.php"
                    class="text-white hover:text-black hover:underline transition hover:bg-orange-300 px-3 md:px-4 py-2 rounded-md">Mesas</a>
                <a href="#"
                    class="text-orange-300 underline hover:text-black hover:underline transition hover:bg-orange-300 px-3 md:px-4 py-2 rounded-md">Produto</a>

                <!-- Botão de Logout -->
                <a href="../logout.php" class="text-white bg-red-600 hover:bg-red-700 px-3 md:px-4 py-2 rounded-md">
                    Sair
                </a>
            </div>
        </div>
    </nav>

    <!-- Conteúdo Principal -->
    <div class="p-6 flex-1">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold">Todos os Produtos</h2>
            <a href="registrarProduto.php"
                class="bg-black text-white  transition duration-50 px-6 py-3 rounded-md hover:bg-orange-600 transition duration-300 ease-in-out">
                Adicionar Produto
            </a>
        </div>

        <!-- Categorias de Produtos -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Pratos -->
            <div class="bg-black p-4 rounded-lg shadow-md relative group">
                <h3 class="text-lg font-semibold mb-2 text-white">Pratos</h3>
                <?php if (count($produtosPratos) > 0): ?>
                    <?php foreach ($produtosPratos as $produto): ?>
                        <div class="p-2 border-b flex items-center space-x-4 relative group">
                            <!-- Exibição da imagem -->
                            <?php if (!empty($produto['imagemProduto'])): ?>
                                <img src="data:image/jpeg;base64,<?= base64_encode($produto['imagemProduto']); ?>"
                                    alt="Imagem do produto" class="w-12 h-12 object-cover rounded-md">
                            <?php endif; ?>

                            <!-- Nome e preço do produto -->
                            <div class="flex-1">
                                <p class="font-semibold text-white"><?= htmlspecialchars($produto['nomeProduto']); ?></p>
                                <p class="text-sm text-gray-500">R$<?= number_format($produto['precoProduto'], 2, ',', '.'); ?>
                                </p>
                            </div>

                            <!-- Botão de editar (aparece quando passar o mouse) -->
                            <a href="editarProduto.php?id=<?= $produto['codProduto']; ?>"
                                class="absolute top-2 right-2 text-white bg-blue-600 hover:bg-blue-700 p-2 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-300 ease-in-out">
                                <i class="fas fa-pencil-alt text-sm"></i>
                            </a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-gray-500">Nenhum item</p>
                <?php endif; ?>
            </div>

            <!-- Sobremesas -->
            <div class="bg-black p-4 rounded-lg shadow-md relative group">
                <h3 class="text-lg font-semibold mb-2 text-white">Sobremesas</h3>
                <?php if (count($produtosSobremesas) > 0): ?>
                    <?php foreach ($produtosSobremesas as $produto): ?>
                        <div class="p-2 border-b flex text-white items-center space-x-4 relative group">
                            <!-- Exibição da imagem -->
                            <?php if (!empty($produto['imagemProduto'])): ?>
                                <img src="data:image/jpeg;base64,<?= base64_encode($produto['imagemProduto']); ?>"
                                    alt="Imagem do produto" class="w-12 h-12 object-cover rounded-md">
                            <?php endif; ?>

                            <!-- Nome e preço do produto -->
                            <div class="flex-1">
                                <p class="font-semibold"><?= htmlspecialchars($produto['nomeProduto']); ?></p>
                                <p class="text-sm text-gray-500">R$<?= number_format($produto['precoProduto'], 2, ',', '.'); ?>
                                </p>
                            </div>

                            <!-- Botão de editar (aparece quando passar o mouse) -->
                            <a href="editarProduto.php?id=<?= $produto['codProduto']; ?>"
                                class="absolute top-2 right-2 text-white bg-blue-600 hover:bg-blue-700 p-2 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-300 ease-in-out">
                                <i class="fas fa-pencil-alt text-sm"></i>
                            </a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-gray-500">Nenhum item</p>
                <?php endif; ?>
            </div>

            <!-- Bebidas -->
            <div class="bg-black p-4 rounded-lg shadow-md relative group">
                <h3 class="text-lg font-semibold mb-2 text-white">Bebidas</h3>
                <?php if (count($produtosBebidas) > 0): ?>
                    <?php foreach ($produtosBebidas as $produto): ?>
                        <div class="p-2 border-b flex text-white items-center space-x-4 relative group">
                            <!-- Exibição da imagem -->
                            <?php if (!empty($produto['imagemProduto'])): ?>
                                <img src="data:image/jpeg;base64,<?= base64_encode($produto['imagemProduto']); ?>"
                                    alt="Imagem do produto" class="w-12 h-12 object-cover rounded-md">
                            <?php endif; ?>

                            <!-- Nome e preço do produto -->
                            <div class="flex-1">
                                <p class="font-semibold"><?= htmlspecialchars($produto['nomeProduto']); ?></p>
                                <p class="text-sm text-gray-500">R$<?= number_format($produto['precoProduto'], 2, ',', '.'); ?>
                                </p>
                            </div>

                            <!-- Botão de editar (aparece quando passar o mouse) -->
                            <a href="editarProduto.php?id=<?= $produto['codProduto']; ?>"
                                class="absolute top-2 right-2 text-white bg-blue-600 hover:bg-blue-700 p-2 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-300 ease-in-out">
                                <i class="fas fa-pencil-alt text-sm"></i>
                            </a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-gray-500">Nenhum item</p>
                <?php endif; ?>
            </div>
        </div>

    </div>
</body>

</html>