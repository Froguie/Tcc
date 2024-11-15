<?php
include("../backend/conexao.php");
session_start();

$mesaSelecionada = "Nenhuma mesa selecionada"; // Mensagem padrão

// Verificar se há uma mesa selecionada na sessão
if (isset($_SESSION['mesaSelecionada'])) {
    // Pegar o número ou ID da mesa da sessão
    $numeroMesa = $_SESSION['mesaSelecionada'];
    
    // Buscar o nome da mesa no banco de dados
    $sql = "SELECT nomeMesa FROM mesa WHERE numero = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $numeroMesa);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $mesaSelecionada = $row['nomeMesa'];
    } else {
        $mesaSelecionada = "Mesa não encontrada";
    }
    $stmt->close();
}

// Obter os detalhes do produto com base no ID
$codProduto = $_GET['id'];
$sql = "SELECT * FROM produto WHERE codProduto = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $codProduto);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
  echo "Produto não encontrado.";
  exit;
}

$produto = $result->fetch_assoc();

// Lidar com o clique no botão "Pedir"
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['adicionarAoCarrinho'])) {
    // Adicionar produto ao carrinho na sessão
    if (!isset($_SESSION['carrinho'])) {
        $_SESSION['carrinho'] = [];
    }
    
    $produtoCarrinho = [
        'codProduto' => $produto['codProduto'],
        'nomeProduto' => $produto['nomeProduto'],
        'quantidade' => 1, // Default: 1 unidade do produto
        'precoProduto' => $produto['precoProduto'],
    ];

    // Adicionar o produto ao carrinho
    $_SESSION['carrinho'][] = $produtoCarrinho;

    // Redirecionar para o carrinho (ou página de finalização do pedido)
    header("Location: carrinho.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Descrição do Produto</title>
  <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-orange-200">

  <!-- Sidebar -->
  <div class="flex min-h-screen">
    <div class="bg-black text-white w-1/4 p-6 flex flex-col justify-between h-screen">
      <div>
        <button class="text-3xl font-bold mb-4">&nbsp;</button>
        <h1 class="text-2xl font-semibold">Brother's Burger</h1>
        <div class="flex justify-between items-center mb-4">
          <p class="text-lg font-semibold text-orange-800">
            Mesa Selecionada: <?php echo $mesaSelecionada; ?>
          </p>
        </div>

        <nav class="mt-8 space-y-4">
          <a href="cardapio.php?categoria=Prato" class="block text-orange-300 hover:text-white transition-colors">Pratos</a>
          <a href="cardapio.php?categoria=Sobremesa" class="block text-orange-300 hover:text-white transition-colors">Sobremesas</a>
          <a href="cardapio.php?categoria=Bebida" class="block text-orange-300 hover:text-white transition-colors">Bebidas</a>
        </nav>
      </div>
      <button class="bg-orange-800 text-orange-300 py-3 mt-8 rounded hover:bg-orange-700 transition duration-300">Finalizar pedido</button>
    </div>

    <!-- Detalhes do Produto -->
    <div class="mx-auto p-10 w-3/4">
      <a href="cardapio.php" class="text-sm bg-red-500 font-semibold text-black mb-4 inline-block px-4 py-2 rounded-lg hover:bg-red-600 transition duration-300">Voltar</a>
      <h2 class="text-3xl font-bold text-black mb-4"><?php echo htmlspecialchars($produto['nomeProduto']); ?></h2>

      <div class="flex items-start space-x-8">
        <!-- Imagem do produto -->
        <div class="w-48 h-48 bg-orange-300 rounded overflow-hidden">
          <img src="data:image/jpeg;base64,<?php echo base64_encode($produto['imagemProduto']); ?>" class="w-full h-full object-cover" alt="Imagem do Produto">
        </div>

        <!-- Informações do produto -->
        <div>
          <p class="text-4xl font-bold text-black mb-4">R$ <?php echo number_format($produto['precoProduto'], 2, ',', '.'); ?></p>
          <p class="text-orange-800"><?php echo nl2br(htmlspecialchars($produto['descricaoProduto'])); ?></p>
        </div>
      </div>

      <!-- Botão de Pedido -->
      <form method="POST" class="mt-6 flex justify-center">
        <button type="submit" name="adicionarAoCarrinho" class="bg-black text-white py-3 px-8 rounded-lg font-semibold hover:bg-orange-900 transition duration-300 focus:outline-none focus:ring-2 focus:ring-orange-300">Pedir</button>
      </form>
    </div>
  </div>

</body>
</html>
