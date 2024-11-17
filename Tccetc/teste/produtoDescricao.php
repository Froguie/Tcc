<?php
include("../backend/conexao.php");
session_start();

$mesaSelecionada = "Nenhuma mesa selecionada"; // Mensagem padrão

// Verificar se há uma mesa selecionada na sessão
if (isset($_SESSION['mesaSelecionada'])) {
  $numeroMesa = $_SESSION['mesaSelecionada'];

  $sql = "SELECT nomeMesa FROM mesa WHERE numero = ?";
  $stmt = $conexao->prepare($sql);
  $stmt->bind_param("i", $numeroMesa);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $mesaSelecionada = htmlspecialchars($row['nomeMesa']);
  } else {
    $mesaSelecionada = "Mesa não encontrada";
  }
  $stmt->close();
}

// Validação de `GET` para o ID do produto
if (!isset($_GET['id']) || empty($_GET['id'])) {
  echo "Produto não especificado.";
  exit;
}

$codProduto = intval($_GET['id']);
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
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['adicionarAoCarrinho'])) {
  if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
  }

  $codProduto = intval($_POST['codProduto']);
  $nomeProduto = htmlspecialchars($_POST['nomeProduto']);
  $precoProduto = floatval($_POST['precoProduto']);
  $imagemProduto = $_POST['imagemProduto'];

  // Verificar se o produto já está no carrinho
  $existe = false;
  foreach ($_SESSION['carrinho'] as &$item) {
    if ($item['codProduto'] === $codProduto) {
      $item['quantidade']++;
      $existe = true;
      break;
    }
  }
  unset($item);

  // Adicionar novo produto se não existir
  if (!$existe) {
    $_SESSION['carrinho'][] = [
      'codProduto' => $codProduto,
      'nomeProduto' => $nomeProduto,
      'quantidade' => 1,
      'precoProduto' => $precoProduto,
      'imagemProduto' => $imagemProduto
    ];
  }

  // Atualizar a quantidade no carrinho
  header("Location: produtoDescricao.php?id=" . $codProduto);
  exit;
}

// Calcular a quantidade total no carrinho
$quantidadeCarrinho = isset($_SESSION['carrinho']) ? array_sum(array_column($_SESSION['carrinho'], 'quantidade')) : 0;
$totalCarrinho = 0;
foreach ($_SESSION['carrinho'] as $item) {
  $totalCarrinho += $item['precoProduto'] * $item['quantidade'];
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
  <script>
    // Gerenciar a exibição da sidebar
    function toggleCarrinho() {
      const carrinhoSidebar = document.getElementById("carrinho-sidebar");
      carrinhoSidebar.classList.toggle("translate-x-full");
    }

    // Remover item do carrinho
    function removerDoCarrinho(codProduto) {
      const form = document.createElement("form");
      form.method = "POST";
      form.action = "removerProdutoCarrinho.php"; // Arquivo PHP para remover o produto
      const input = document.createElement("input");
      input.type = "hidden";
      input.name = "codProduto";
      input.value = codProduto;
      form.appendChild(input);
      document.body.appendChild(form);
      form.submit();
    }
  </script>
</head>

<body class="bg-orange-200">

  <!-- Sidebar -->
  <div class="flex min-h-screen">
    <div class="bg-black text-white w-1/4 p-6 flex flex-col justify-between h-screen">
      <div>
        <h1 class="text-2xl font-semibold">Brother's Burger</h1>
        <div class="flex justify-between items-center mb-4">
          <p class="text-lg font-semibold text-orange-800">
            Mesa Selecionada: <?php echo $mesaSelecionada; ?>
          </p>
        </div>

        <nav class="mt-8 space-y-4">
          <a href="cardapio.php?categoria=Prato"
            class="block text-orange-300 hover:text-white transition-colors">Pratos</a>
          <a href="cardapio.php?categoria=Sobremesa"
            class="block text-orange-300 hover:text-white transition-colors">Sobremesas</a>
          <a href="cardapio.php?categoria=Bebida"
            class="block text-orange-300 hover:text-white transition-colors">Bebidas</a>
        </nav>
      </div>
      <a href="carrinho.php"
        class="bg-orange-800 text-orange-300 py-3 mt-8 rounded hover:bg-orange-700 transition duration-300 text-center block">Finalizar
        pedido</a>
    </div>

    <!-- Detalhes do Produto -->
    <div class="mx-auto p-10 w-3/4">
      <a href="cardapio.php"
        class="text-sm bg-red-500 font-semibold text-black mb-4 inline-block px-4 py-2 rounded-lg hover:bg-red-600 transition duration-300">Voltar</a>
      <h2 class="text-3xl font-bold text-black mb-4"><?php echo htmlspecialchars($produto['nomeProduto']); ?></h2>

      <div class="flex items-start space-x-8">
        <!-- Imagem do produto -->
        <div class="w-48 h-48 bg-orange-300 rounded overflow-hidden">
          <?php if (!empty($produto['imagemProduto'])): ?>
            <img src="data:image/jpeg;base64,<?php echo base64_encode($produto['imagemProduto']); ?>"
              class="w-full h-full object-cover" alt="Imagem do Produto">
          <?php else: ?>
            <p class="text-center text-gray-500">Sem imagem disponível</p>
          <?php endif; ?>
        </div>

        <!-- Informações do produto -->
        <div>
          <p class="text-4xl font-bold text-black mb-4">R$
            <?php echo number_format($produto['precoProduto'], 2, ',', '.'); ?>
          </p>
          <p class="text-orange-800"><?php echo nl2br(htmlspecialchars($produto['descricaoProduto'])); ?></p>
        </div>
      </div>

      <!-- Botão de Pedido -->
      <div class="mt-6 flex justify-center">
        <form method="POST">
          <input type="hidden" name="codProduto" value="<?php echo $produto['codProduto']; ?>" />
          <input type="hidden" name="nomeProduto" value="<?php echo htmlspecialchars($produto['nomeProduto']); ?>" />
          <input type="hidden" name="precoProduto" value="<?php echo $produto['precoProduto']; ?>" />
          <input type="hidden" name="imagemProduto" value="<?php echo base64_encode($produto['imagemProduto']); ?>" />
          <button name="adicionarAoCarrinho" type="submit"
            class="bg-black text-white py-3 px-8 rounded-lg font-semibold hover:bg-orange-900 transition duration-300 focus:outline-none focus:ring-2 focus:ring-orange-300">
            Adicionar ao Carrinho
          </button>
        </form>
      </div>
    </div>
  </div>

  <!-- Carrinho -->
  <div id="carrinho-sidebar"
    class="fixed right-0 top-0 w-80 bg-white p-6 pb-16 h-full transform translate-x-full transition-transform">
    <h2 class="text-xl font-semibold mb-4">Carrinho</h2>
    <!-- Verifique se há itens no carrinho -->
    <?php if (isset($_SESSION['carrinho']) && !empty($_SESSION['carrinho'])): ?>
      <div id="carrinho-itens" class="space-y-4 overflow-y-auto">
        <?php foreach ($_SESSION['carrinho'] as $item): ?>
          <div class="flex justify-between items-center">
            <div class="flex items-center space-x-4">
              <!-- Verifique se a imagem está corretamente definida -->
              <?php if (!empty($item['imagemProduto'])): ?>
                <img src="data:image/jpeg;base64,<?php echo $item['imagemProduto']; ?>" class="w-12 h-12 object-cover"
                  alt="Produto">
              <?php else: ?>
                <img src="default-image.jpg" class="w-12 h-12 object-cover" alt="Produto">
              <?php endif; ?>

              <div>
                <p class="text-sm"><?php echo htmlspecialchars($item['nomeProduto']); ?></p>
                <p class="text-sm text-gray-500">R$ <?php echo number_format($item['precoProduto'], 2, ',', '.'); ?> x
                  <?php echo $item['quantidade']; ?>
                </p>
              </div>
            </div>

            <button onclick="removerDoCarrinho(<?php echo $item['codProduto']; ?>)"
              class="text-red-500 hover:text-red-700">Remover</button>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <p class="text-center text-gray-500">Seu carrinho está vazio.</p>
    <?php endif; ?>

    <div class="mt-4 text-xl font-bold">
      Total: R$ <?php echo number_format($totalCarrinho, 2, ',', '.'); ?>
    </div>

    <button onclick="toggleCarrinho()" class="mt-4 text-black">&times</button>
  </div>

  <!-- Carrinho flutuante -->
  <button onclick="toggleCarrinho()"
    class="fixed bottom-6 right-6 bg-orange-800 text-white py-3 px-6 rounded-full shadow-lg">
    <span>Ver Carrinho (<?php echo $quantidadeCarrinho; ?>)</span>
  </button>

</body>

</html>