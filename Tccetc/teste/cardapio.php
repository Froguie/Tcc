<?php
include("../backend/conexao.php");
session_start();

// Verificar a categoria selecionada
$categoriaSelecionada = isset($_GET['categoria']) ? $_GET['categoria'] : 'todos';

// Obter produtos por categoria
$sql = "SELECT * FROM produto";
if ($categoriaSelecionada != 'todos') {
    $sql .= " WHERE categoriaProduto = '$categoriaSelecionada'";
}
$result = $conexao->query($sql);

$produtos = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $produtos[] = $row;
    }
} else {
    echo "<p class='text-center text-gray-600'>Nenhum produto encontrado.</p>";
}

// Consulta as mesas disponíveis no banco de dados
$mesas = [];
$result = $conexao->query("SELECT numero FROM mesa WHERE statusMesa = 'ocupada' ORDER BY numero ASC");
if ($result && $result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $mesas[] = $row['numero'];
  }
}

// Captura o número da mesa selecionada ao enviar o formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mesaSelecionada']) && !empty($_POST['mesaSelecionada'])) {
  $_SESSION['mesaSelecionada'] = $_POST['mesaSelecionada'];

  // Redireciona para evitar reenvio do formulário
  header("Location: " . $_SERVER['PHP_SELF']);
  exit;
}

// Exibe um alerta se nenhuma mesa foi selecionada
$mensagemMesa = '';
if (!isset($_SESSION['mesaSelecionada'])) {
  $mensagemMesa = "<p class='text-center text-red-500'>Por favor, selecione a mesa antes de fazer o pedido.</p>";
} else {
  $mensagemMesa = "<p class='text-center text-green-500'>Mesa {$_SESSION['mesaSelecionada']} selecionada.</p>";
}

// Obter produtos por categoria
$sql = "SELECT * FROM produto WHERE categoriaProduto IN ('Prato', 'Sobremesa', 'Bebida')";
$result = $conexao->query($sql);

$pratos = [];
$sobremesas = [];
$bebidas = [];
$todosProdutos = [];

if ($result && $result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $todosProdutos[] = $row;

    switch ($row['categoriaProduto']) {
      case 'Prato':
        $pratos[] = $row;
        break;
      case 'Sobremesa':
        $sobremesas[] = $row;
        break;
      case 'Bebida':
        $bebidas[] = $row;
        break;
    }
  }
} else {
  echo "<p class='text-center text-gray-600'>Erro ao carregar produtos.</p>";
}

// Função para adicionar produto ao pedido da mesa
if (isset($_GET['adicionar_produto'])) {
  $produtoId = $_GET['adicionar_produto'];
  $mesaSelecionada = $_SESSION['mesaSelecionada'];

  // Verifica se a mesa foi selecionada
  if (isset($mesaSelecionada) && !empty($mesaSelecionada)) {
    // Adiciona o pedido no banco
    $sqlPedido = "INSERT INTO pedido (codProPe, numeroMesa, statusPedido) VALUES (?, ?, 'aberto')";
    $stmt = $conexao->prepare($sqlPedido);
    $stmt->bind_param("ii", $produtoId, $mesaSelecionada);
    $stmt->execute();
    $stmt->close();
  }
  // Redireciona para evitar reenvio do formulário
  header("Location: " . $_SERVER['PHP_SELF']);
  exit;
}

// Função para pegar pedidos de uma mesa específica
function getPedidosPorMesa($numeroMesa)
{
  global $conexao;
  $sql = "SELECT p.nomeProduto, p.precoProduto, pe.quantidade
          FROM pedido pe
          JOIN produto p ON pe.codProPe = p.codProduto
          WHERE pe.numeroMesa = ? AND pe.statusPedido = 'aberto'";
  $stmt = $conexao->prepare($sql);
  $stmt->bind_param("i", $numeroMesa);
  $stmt->execute();
  $result = $stmt->get_result();
  $pedidos = [];
  while ($row = $result->fetch_assoc()) {
    $pedidos[] = $row;
  }
  return $pedidos;
}

// Calcular a quantidade total no carrinho
if (isset($_SESSION['carrinho']) && is_array($_SESSION['carrinho'])) {
  $quantidadeCarrinho = array_sum(array_column($_SESSION['carrinho'], 'quantidade'));
$totalCarrinho = 0;
foreach ($_SESSION['carrinho'] as $item) {
  // Garante que 'precoProduto' e 'quantidade' estão definidos
  if (isset($item['precoProduto']) && isset($item['quantidade'])) {
      $totalCarrinho += $item['precoProduto'] * $item['quantidade'];
  }
}
} else {
// Caso não haja carrinho ou esteja vazio, define valores padrão
$quantidadeCarrinho = 0;
$totalCarrinho = 0;
}


// Lógica para remover um item do carrinho
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remover_produto'])) {
  $codProdutoRemover = $_POST['remover_produto'];

  if (isset($_SESSION['carrinho']) && !empty($_SESSION['carrinho'])) {
      foreach ($_SESSION['carrinho'] as $key => $item) {
          if ($item['codProduto'] == $codProdutoRemover) {
              unset($_SESSION['carrinho'][$key]);
              break;
          }
      }
      $_SESSION['carrinho'] = array_values($_SESSION['carrinho']);
  }
  header("Location: " . $_SERVER['PHP_SELF']);
  exit;
}
// Supondo que o item seja adicionado ao carrinho
if (isset($_POST['adicionar'])) {
  $numeroMesa = $_POST['numeroMesa'];
  $item = $_POST['item'];
  $quantidade = $_POST['quantidade'];
  $preco = $_POST['preco'];
  // Verifica se já existe um carrinho na sessão
  if (!isset($_SESSION['carrinhos'][$numeroMesa])) {
    $_SESSION['carrinhos'][$numeroMesa] = []; // Cria o carrinho se não existir
    }
    // Adiciona o item ao carrinho da mesa
    $_SESSION['carrinhos'][$numeroMesa][] = [
      'item' => $item,
      'quantidade' => $quantidade,
      'preco' => $preco
  ];
  // Redireciona para a página de mesas
  header('Location: caixa.php');
  exit();
  }
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cardápio</title>
  <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    // Gerenciar a exibição da sidebar do carrinho
    function toggleCarrinho() {
      const carrinhoSidebar = document.getElementById("carrinho-sidebar");
      carrinhoSidebar.classList.toggle("translate-x-full");
    }
  </script>
</head>

<body class="bg-orange-200">

  <!-- Sidebar -->
  <div id="sidebar" class="bg-black text-white w-64 p-6 flex flex-col justify-between h-screen fixed left-0 top-0">
    <h2 class="text-2xl font-bold mb-8 text-center">Brother's Burger</h2>

    <!-- Formulário para Seleção de Mesa -->
    <form method="POST" class="mb-4">
      <div class="flex justify-between items-center mb-4">
        <select id="mesaSelect" name="mesaSelecionada" class="text-black bg-white rounded-md px-2 py-1 w-full">
          <option value="">Selecione a Mesa</option>
          <?php foreach ($mesas as $numeroMesa): ?>
            <option value="<?php echo $numeroMesa; ?>" <?php echo (isset($_SESSION['mesaSelecionada']) && $_SESSION['mesaSelecionada'] == $numeroMesa) ? 'selected' : ''; ?>>
              Mesa <?php echo $numeroMesa; ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <button type="submit" class="bg-orange-500 text-white py-2 px-4 w-full rounded-lg hover:bg-orange-600">
        Confirmar Mesa
      </button>
    </form>

    <!-- Mensagem de Seleção de Mesa -->
    <?php echo $mensagemMesa; ?>

    <!-- Links de Categorias -->
    <ul class="space-y-4 text-lg">
    <li class="cursor-pointer hover:text-orange-300 transition-all">
        <a href="?categoria=todos" class="block">Todos os Produtos</a>
    </li>
    <li class="cursor-pointer hover:text-orange-300 transition-all">
        <a href="?categoria=prato" class="block">Pratos</a>
    </li>
    <li class="cursor-pointer hover:text-orange-300 transition-all">
        <a href="?categoria=sobremesa" class="block">Sobremesas</a>
    </li>
    <li class="cursor-pointer hover:text-orange-300 transition-all">
        <a href="?categoria=bebida" class="block">Bebidas</a>
    </li>
</ul>

    <button class="bg-orange-500 text-white py-3 mt-8 rounded text-lg hover:bg-orange-600" onclick="finalizarPedido()">
      Finalizar pedido
      <span id="badgeCarrinho" class="ml-2 text-white bg-red-500 rounded-full px-2 py-1 text-sm hidden"></span>
    </button>

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
                <p class="text-sm text-gray-500"><?php echo htmlspecialchars($item['nomeProduto']); ?></p>
                <p class="text-sm text-gray-500">R$ <?php echo number_format($item['precoProduto'], 2, ',', '.'); ?> x
                  <?php echo $item['quantidade']; ?>
                </p>
              </div>
            </div>

            <!-- Botão de Remover -->
          <form method="POST" class="inline">
            <input type="hidden" name="remover_produto" value="<?php echo $item['codProduto']; ?>">
            <button type="submit" class="text-red-500 hover:text-red-700">Remover</button>
          </form>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <p class="text-center text-gray-500">Seu carrinho está vazio.</p>
    <?php endif; ?>

    <div class="mt-4 text-xl font-bold">
      Total: R$ <?php echo number_format($totalCarrinho, 2, ',', '.'); ?>
    </div>

    
  </div>

    <!-- Botão de Ver Carrinho -->
    <button onclick="toggleCarrinho()" class="fixed bottom-6 right-6 bg-orange-800 text-white py-3 px-6 rounded-full shadow-lg">
      <span>Ver Carrinho (<?php echo $quantidadeCarrinho; ?>)</span>
    </button>
  </div>

  <!-- Conteúdo Principal -->
  <div class="ml-64 p-8">
    <h3 class="text-3xl font-semibold mb-8 text-orange-500"></h3>
    <div id="todos" class="tab-content grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6">
      <?php foreach ($produtos as $produto): ?>
        <a href="produtoDescricao.php?id=<?php echo $produto['codProduto']; ?>">
          <div class="bg-white rounded-lg shadow-lg p-4 cursor-pointer hover:shadow-xl transition-shadow duration-300">
            <div class="w-full h-40 bg-gray-400 rounded-md overflow-hidden">
              <img src="data:image/jpeg;base64,<?php echo base64_encode($produto['imagemProduto']); ?>"
                class="w-full h-full object-cover" alt="<?php echo htmlspecialchars($produto['nomeProduto']); ?>">
            </div>
            <p class="text-orange-700 font-semibold mt-4"><?php echo htmlspecialchars($produto['nomeProduto']); ?></p>
            <p class="text-gray-600">R$ <?php echo number_format($produto['precoProduto'], 2, ',', '.'); ?></p>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
  </div>

  <script>
    function finalizarPedido() {
      window.location.href = "carrinho.php"; // redireciona para a página de carrinho
    }
  </script>
</body>

</html>
