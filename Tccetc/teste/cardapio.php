<?php
include("../backend/conexao.php");
session_start();

// Consulta as mesas disponíveis no banco de dados
$mesas = [];
$result = $conexao->query("SELECT numero FROM mesa WHERE statusMesa = 'livre' ORDER BY numero ASC");
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

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cardápio</title>
  <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
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
      <li class="cursor-pointer hover:text-orange-300 transition-all" onclick="showTab('todos')">Todos os Produtos</li>
      <li class="cursor-pointer hover:text-orange-300 transition-all" onclick="showTab('pratos')">Pratos</li>
      <li class="cursor-pointer hover:text-orange-300 transition-all" onclick="showTab('sobremesas')">Sobremesas</li>
      <li class="cursor-pointer hover:text-orange-300 transition-all" onclick="showTab('bebidas')">Bebidas</li>
    </ul>

    <button class="bg-orange-500 text-white py-3 mt-8 rounded text-lg hover:bg-orange-600" onclick="finalizarPedido()">
      Finalizar pedido
      <span id="badgeCarrinho" class="ml-2 text-white bg-red-500 rounded-full px-2 py-1 text-sm hidden"></span>
    </button>
  </div>

  <!-- Conteúdo Principal -->
  <div class="ml-64 p-8">
    <h3 class="text-3xl font-semibold mb-8 text-orange-500">Todos os Produtos</h3>
    <div id="todos" class="tab-content grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6">
      <?php foreach ($todosProdutos as $produto): ?>
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
</body>

</html>