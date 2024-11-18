<?php
session_start();
include("../backend/conexao.php");

// Função para obter o produto pelo ID
function obterProduto($id)
{
    global $conexao;
    $query = "SELECT * FROM produto WHERE codProduto = ?";
    $stmt = $conexao->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

// Função para atualizar o produto no banco de dados
function atualizarProduto($id, $nome, $preco, $descricao, $categoria, $imagem)
{
    global $conexao;
    $query = "UPDATE produto SET nomeProduto = ?, precoProduto = ?, descricaoProduto = ?, categoriaProduto = ?, imagemProduto = ? WHERE codProduto = ?";
    $stmt = $conexao->prepare($query);
    $stmt->bind_param("sdsssi", $nome, $preco, $descricao, $categoria, $imagem, $id);
    return $stmt->execute();
}

// Função para excluir o produto
function deletarProduto($id)
{
    global $conexao;
    $query = "DELETE FROM produto WHERE codProduto = ?";
    $stmt = $conexao->prepare($query);
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}

// Verifica se o ID foi passado
if (isset($_GET['id'])) {
    $produto = obterProduto($_GET['id']);
    if (!$produto) {
        header("Location: produtos.php");
        exit();
    }
} else {
    header("Location: produtos.php");
    exit();
}

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['deletar'])) { // Exclusão
        if (deletarProduto($_GET['id'])) {
            header("Location: produtos.php");
            exit();
        } else {
            $erro = "Erro ao deletar o produto.";
        }
    } else { // Atualização
        $nome = $_POST['nome'];
        $preco = $_POST['preco'];
        $descricao = $_POST['descricao'];
        $categoria = $_POST['categoria'];

        // Processa a imagem
        if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == 0) {
            $imagem = file_get_contents($_FILES['imagem']['tmp_name']);
        } else {
            $imagem = $produto['imagemProduto']; // Mantém a imagem atual
        }

        // Atualiza o produto
        if (atualizarProduto($_GET['id'], $nome, $preco, $descricao, $categoria, $imagem)) {
            header("Location: produtos.php");
            exit();
        } else {
            $erro = "Erro ao atualizar o produto.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Produto</title>
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
            <div id="menu"
                class="hidden w-full md:flex md:w-auto flex-col md:flex-row items-center md:space-x-4 mt-4 md:mt-0">
                <a href="produtos.php"
                    class="text-white hover:text-black hover:underline transition hover:bg-orange-300 px-3 md:px-4 py-2 rounded-md">Produtos</a>
                <a href="caixa.php"
                    class="text-white hover:text-black hover:underline transition hover:bg-orange-300 px-3 md:px-4 py-2 rounded-md">Caixa</a>
                <a href="mesa.php"
                    class="text-white hover:text-black hover:underline transition hover:bg-orange-300 px-3 md:px-4 py-2 rounded-md">Mesas</a>
                <a href="logout.php"
                    class="text-white bg-red-600 hover:bg-red-700 px-3 md:px-4 py-2 rounded-md">Sair</a>
            </div>
        </div>
    </nav>

    <!-- Breadcrumb -->
    <div class="flex items-center space-x-2 px-6 py-4 bg-orange-200">
        <a href="produtos.php" class="text-orange-500 hover:underline">Produtos</a>
        <span>/</span>
        <span>Editar Produto</span>
    </div>

    <!-- Conteúdo Principal -->
    <div class="p-6 flex-1">
        <h2 class="text-xl font-semibold mb-4">Editar Produto</h2>

        <?php if (isset($erro)): ?>
            <div class="text-red-600 mb-4"><?= $erro; ?></div>
        <?php endif; ?>

        <!-- Formulário de Edição de Produto -->
        <form action="editarProduto.php?id=<?= $produto['codProduto']; ?>" method="POST" enctype="multipart/form-data"
            class="space-y-6">
            <div class="flex space-x-4">
                <div class="flex-1">
                    <label for="nome" class="block text-sm font-medium text-gray-700">Nome</label>
                    <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($produto['nomeProduto']); ?>"
                        class="mt-1 p-2 w-full border rounded-md">

                    <label for="preco" class="block text-sm font-medium text-gray-700 mt-4">Preço</label>
                    <input type="number" id="preco" name="preco"
                        value="<?= number_format($produto['precoProduto'], 2, '.', ''); ?>"
                        class="mt-1 p-2 w-full border rounded-md" step="0.01">
                </div>

                <div class="flex-1">
                    <label for="descricao" class="block text-sm font-medium text-gray-700">Descrição</label>
                    <textarea id="descricao" name="descricao" class="mt-1 p-2 w-full border rounded-md"
                        rows="5"><?= htmlspecialchars($produto['descricaoProduto']); ?></textarea>
                </div>

                <div class="flex-1">
                    <label for="imagem" class="block text-sm font-medium text-gray-700">Imagem</label>
                    <input type="file" id="imagem" name="imagem" class="mt-1 p-2 w-full border rounded-md"
                        accept="image/*">
                </div>
            </div>

            <div>
                <label for="categoria" class="block text-sm font-medium text-gray-700">Categoria</label>
                <select id="categoria" name="categoria" class="mt-1 p-2 w-full border rounded-md">
                    <option value="Prato" <?= $produto['categoriaProduto'] == 'Prato' ? 'selected' : ''; ?>>Prato</option>
                    <option value="Sobremesa" <?= $produto['categoriaProduto'] == 'Sobremesa' ? 'selected' : ''; ?>>
                        Sobremesa</option>
                    <option value="Bebida" <?= $produto['categoriaProduto'] == 'Bebida' ? 'selected' : ''; ?>>Bebida
                    </option>
                </select>
            </div>

            <div class="flex justify-between mt-6">
                <button type="submit"
                    class="bg-black text-white px-6 py-3 rounded-md hover:bg-orange-600 transition duration-300 ease-in-out">
                    Atualizar Produto
                </button>

                <button type="submit" name="deletar"
                    class="bg-red-600 text-white px-6 py-3 rounded-md hover:bg-red-700 transition duration-300 ease-in-out">
                    Deletar Produto
                </button>
            </div>
        </form>
    </div>
</body>

</html>
