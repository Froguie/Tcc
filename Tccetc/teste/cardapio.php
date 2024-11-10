<?php
include("../backend/conexao.php");
session_start();

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
    <div class="flex justify-between items-center mb-4">
      <button class="text-3xl text-white hover:text-gray-400">
        &nbsp;
      </button><span class="text-gray-400">Mesa 0</span>
    </div>
    <h2 class="text-2xl font-bold mb-8 text-center">Brother's Burger</h2>
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

  <!-- Seções de Produtos por Categoria -->
  <div class="ml-64 p-8">
    <h3 class="text-3xl font-semibold mb-8 text-orange-700">Todos os Produtos</h3>
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6">
      <?php foreach ($todosProdutos as $produto): ?>
        <div class="bg-white rounded-lg shadow-lg p-4 cursor-pointer"
          onclick="addToCart('<?php echo $produto['nomeProduto']; ?>', <?php echo $produto['precoProduto']; ?>)">
          <div class="w-full h-40 bg-gray-400 rounded-md overflow-hidden">
            <img src="data:image/jpeg;base64,<?php echo base64_encode($produto['imagemProduto']); ?>"
              class="w-full h-full object-cover">
          </div>
          <p class="text-orange-700 font-semibold mt-4"><?php echo $produto['nomeProduto']; ?></p>
          <p class="text-gray-600">R$ <?php echo number_format($produto['precoProduto'], 2, ',', '.'); ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
  <!-- Sidebar de Detalhes do Produto -->
  <div id="productSidebar"
    class="fixed top-0 right-0 h-full w-80 bg-white p-6 shadow-lg transform translate-x-full transition-transform duration-300">
    <button onclick="hideProductSidebar()" class="text-2xl text-gray-500 hover:text-gray-700 absolute top-4 right-4">
      &times;
    </button>
    <div id="productDetails">
      <h2 id="productName" class="text-2xl font-semibold text-orange-700 mb-3"></h2>
      <div id="productImage" class="w-full h-48 bg-gray-200 rounded-md mb-4 overflow-hidden">
        <img id="productImgSrc" src="" alt="" class="w-full h-full object-cover">
      </div>
      <p id="productPrice" class="text-lg font-semibold text-gray-700 mb-4"></p>
      <div class="flex items-center mb-4">
        <label for="quantity" class="text-gray-600 mr-2">Quantidade:</label>
        <input type="number" id="quantity"
          class="p-2 w-20 border rounded-md text-center focus:outline-none focus:ring-2 focus:ring-orange-500" value="1"
          min="1">
      </div>
      <button onclick="addToCart()"
        class="bg-orange-500 text-white py-2 w-full rounded-lg text-lg hover:bg-orange-600 transition-all">
        Adicionar ao Carrinho
      </button>
    </div>
  </div>

  <!-- Popup Temporário do Carrinho -->
  <div id="popup" class="fixed top-4 right-4 p-4 bg-green-500 text-white rounded-lg shadow-md hidden">
    Produto adicionado ao carrinho!
  </div>

  <script>
    function showTab(tab) {
      // Esconde todas as seções de produtos
      const tabs = document.querySelectorAll('.tab-content');
      tabs.forEach((tabContent) => {
        tabContent.style.display = 'none';
      });

      // Exibe a seção de produtos selecionada
      const selectedTab = document.getElementById(tab);
      if (selectedTab) {
        selectedTab.style.display = 'block';
      }
    }

    // Exemplo de chamar showTab para "todos os produtos"
    showTab('todos');


    let cart = [];

    function showProductDetails(produto) {
      if (!produto || !produto.nomeProduto || !produto.imagemProduto) {
        console.error("Produto inválido.");
        return;
      }

      document.getElementById("productName").innerText = produto.nomeProduto;
      document.getElementById("productImgSrc").src = "data:image/jpeg;base64," + produto.imagemProduto;
      document.getElementById("productPrice").innerText = "R$ " + parseFloat(produto.precoProduto).toFixed(2).replace('.', ',');

      const sidebar = document.getElementById("productSidebar");
      sidebar.classList.remove("translate-x-full");
      sidebar.classList.add("translate-x-0");
    }

    function hideProductSidebar() {
      const sidebar = document.getElementById("productSidebar");
      sidebar.classList.remove("translate-x-0");
      sidebar.classList.add("translate-x-full");
    }

    function toggleCart() {
      const badgeCarrinho = document.getElementById("badgeCarrinho");

      // Se o carrinho tiver itens, mostra o número de itens no badge
      if (cart.length > 0) {
        badgeCarrinho.innerText = cart.length;
        badgeCarrinho.classList.remove("hidden");
      } else {
        badgeCarrinho.classList.add("hidden");
      }
    }

    function addToCart() {
      const productName = document.getElementById("productName").innerText;
      const quantity = document.getElementById("quantity").value;

      if (!productName || quantity <= 0) {
        alert("Selecione um produto válido e uma quantidade.");
        return;
      }

      function showTab(tab) {
        // Esconde todas as seções de produtos
        const tabs = document.querySelectorAll('.tab-content');
        tabs.forEach((tabContent) => {
          tabContent.style.display = 'none';
        });

        // Exibe a seção de produtos selecionada
        const selectedTab = document.getElementById(tab);
        if (selectedTab) {
          selectedTab.style.display = 'block';
        }
      }

      // Definir a tab inicial
      showTab('todos');


      // Adicionar o produto ao carrinho
      const product = {
        name: productName,
        quantity: parseInt(quantity),
      };
      cart.push(product);
      toggleCart(); // Atualizar badge com quantidade

      // Exibir popup temporário
      const popup = document.getElementById("popup");
      popup.classList.remove("hidden");
      setTimeout(() => {
        popup.classList.add("hidden");
      }, 3000); // Esconder após 3 segundos
    }

    function finalizarPedido() {
      if (cart.length === 0) {
        alert('Seu carrinho está vazio!');
        return;
      }

      // Exibir dados do carrinho no console para depuração
      console.log('Carrinho:', cart);

      fetch('finalizar_pedido.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(cart),
      })
        .then(response => response.json())
        .then(data => {
          console.log('Resposta do servidor:', data);
          if (data.success) {
            // Redirecionar para a página de checkout
            window.location.href = 'checkout.php'; // Substitua 'checkout.php' pela sua página de checkout
          } else {
            alert('Erro ao finalizar pedido: ' + (data.message || 'Erro desconhecido.'));
          }
        })
        .catch(error => {
          console.error('Erro:', error);
          alert('Ocorreu um erro ao finalizar o pedido.');
        });
    }

  </script>
  <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
</body>

</html>