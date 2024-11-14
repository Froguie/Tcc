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
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Descrição do Produto</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-200">

  <!-- Sidebar -->
  <div class="flex min-h-screen">
    <div class="bg-black text-white w-1/4 p-6 flex flex-col justify-between h-screen">
      <div>
        <button class="text-3xl font-bold mb-4">&times;</button>
        <h1 class="text-2xl font-semibold">Brother's Burger</h1>
        <div class="flex justify-between items-center mb-4">
  <p class="text-lg font-semibold text-gray-800">
    Mesa Selecionada: <?php echo $mesaSelecionada; ?>
  </p>
</div>

        <nav class="mt-8 space-y-4">
          <a href="cardapio.php?categoria=Prato" class="block text-gray-300 hover:text-white">Pratos</a>
          <a href="cardapio.php?categoria=Sobremesa" class="block text-gray-300 hover:text-white">Sobremesas</a>
          <a href="cardapio.php?categoria=Bebida" class="block text-gray-300 hover:text-white">Bebidas</a>
        </nav>
      </div>
      <button class="bg-gray-800 text-gray-300 py-3 mt-8 rounded hover:bg-gray-700">Finalizar pedido</button>
    </div>

    <!-- Detalhes do Produto -->
    <div class="mx-auto p-10 bg-orange-400 w-3/4">
      <a href="cardapio.php" class="text-sm font-semibold text-black mb-4 inline-block">Voltar</a>
      <h2 class="text-3xl font-bold text-black mb-4"><?php echo htmlspecialchars($produto['nomeProduto']); ?></h2>

      <div class="flex items-start space-x-8">
        <!-- Imagem do produto -->
        <div class="w-48 h-48 bg-gray-300 rounded overflow-hidden">
          <img src="data:image/jpeg;base64,<?php echo base64_encode($produto['imagemProduto']); ?>" class="w-full h-full object-cover" alt="Imagem do Produto">
        </div>

        <!-- Informações do produto -->
        <div>
          <p class="text-4xl font-bold text-black mb-4">R$ <?php echo number_format($produto['precoProduto'], 2, ',', '.'); ?></p>
          <p class="text-gray-800"><?php echo nl2br(htmlspecialchars($produto['descricaoProduto'])); ?></p>
        </div>
      </div>

      <button class="mt-6 bg-black text-white py-3 px-8 rounded font-semibold hover:bg-gray-900">Pedir</button>
    </div>
  </div>

</body>
</html>
