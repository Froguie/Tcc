<?php
session_start();
include("../backend/conexao.php");

// Função para salvar o produto no banco de dados
function salvarProduto($nome, $preco, $descricao, $categoria, $imagem) {
    global $conexao;
    $query = "INSERT INTO produto (nomeProduto, precoProduto, descricaoProduto, categoriaProduto, imagemProduto) 
              VALUES (?, ?, ?, ?, ?)";
    $stmt = $conexao->prepare($query);
    $stmt->bind_param("sdsss", $nome, $preco, $descricao, $categoria, $imagem);
    return $stmt->execute();
}

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $preco = $_POST['preco'];
    $descricao = $_POST['descricao'];
    $categoria = $_POST['categoria'];

    // Processa a imagem
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == 0) {
        $imagem = file_get_contents($_FILES['imagem']['tmp_name']);
    } else {
        $imagem = null;
    }

    // Chama a função para salvar o produto
    if (salvarProduto($nome, $preco, $descricao, $categoria, $imagem)) {
        header("Location: produtos.php");
        exit();
    } else {
        $erro = "Erro ao adicionar o produto.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Produto</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-orange-300 min-h-screen flex flex-col">
    <!-- Navbar -->
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

    <!-- Breadcrumb -->
    <div class="flex items-center space-x-2 px-6 py-4 bg-orange-200">
        <a href="produtos.php" class="text-orange-500 hover:underline">Produtos</a>
        <span>/</span>
        <span>Adicionar Produto</span>
    </div>

    <!-- Conteúdo Principal -->
    <div class="p-6 flex-1">
        <h2 class="text-xl font-semibold mb-4">Adicionar Novo Produto</h2>

        <?php if (isset($erro)): ?>
            <div class="text-red-600 mb-4"><?= $erro; ?></div>
        <?php endif; ?>

        <!-- Formulário de Adição de Produto -->
        <form action="registrarProduto.php" method="POST" enctype="multipart/form-data" class="space-y-6">
            <div class="flex space-x-4">
                <!-- Nome e Preço -->
                <div class="flex-1">
                    <label for="nome" class="block text-sm font-medium text-gray-700">Nome</label>
                    <input type="text" id="nome" name="nome" class="mt-1 p-2 w-full border rounded-md" required>

                    <label for="preco" class="block text-sm font-medium text-gray-700 mt-4">Preço</label>
                    <input type="number" id="preco" name="preco" class="mt-1 p-2 w-full border rounded-md" step="0.01" required>
                </div>

                <!-- Descrição -->
                <div class="flex-1">
                    <label for="descricao" class="block text-sm font-medium text-gray-700">Descrição</label>
                    <textarea id="descricao" name="descricao" class="mt-1 p-2 w-full border rounded-md" rows="5" required></textarea>
                </div>

                <!-- Imagem -->
                <div class="flex-1">
                    <label for="imagem" class="block text-sm font-medium text-gray-700">Imagem</label>
                    <input type="file" id="imagem" name="imagem" class="mt-1 p-2 w-full border rounded-md" accept="image/*" required>
                </div>
            </div>

            <!-- Categoria -->
            <div>
                <label for="categoria" class="block text-sm font-medium text-gray-700">Categoria</label>
                <select id="categoria" name="categoria" class="mt-1 p-2 w-full border rounded-md" required>
                    <option value="Prato">Prato</option>
                    <option value="Sobremesa">Sobremesa</option>
                    <option value="Bebida">Bebida</option>
                </select>
            </div>

            <div class="flex justify-end mt-6">
                <button type="submit" class="bg-black text-white px-6 py-3 rounded-md hover:bg-orange-600 transition duration-300 ease-in-out">Adicionar Produto</button>
            </div>
        </form>
    </div>
</body>

</html>
