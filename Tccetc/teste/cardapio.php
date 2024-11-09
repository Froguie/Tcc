<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cardápio</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    html {
      scroll-behavior: smooth;
    }
    body, html {
      height: 100%;
    }
    #details-sidebar {
      transition: transform 0.3s ease-in-out;
    }
  </style>
</head>

<body class="bg-orange-100">
  <div class="flex h-full">
    <!-- Sidebar -->
    <div id="sidebar" class="bg-black text-white w-1/4 p-6 flex flex-col justify-between h-full lg:w-1/4 lg:block absolute lg:relative transform lg:transform-none transition-all duration-300 z-10">
      <div>
        <button onclick="toggleSidebar()" class="text-3xl font-bold text-white hover:text-gray-400 mb-6">
          &times;
        </button>
        <h2 class="text-3xl font-semibold mb-8 text-center">Brother's Burger</h2>
        <ul class="space-y-6 text-lg">
          <li class="cursor-pointer hover:text-orange-300 transition-all" onclick="scrollToCategory('pratos')">Pratos</li>
          <li class="cursor-pointer hover:text-orange-300 transition-all" onclick="scrollToCategory('sobremesas')">Sobremesas</li>
          <li class="cursor-pointer hover:text-orange-300 transition-all" onclick="scrollToCategory('bebidas')">Bebidas</li>
        </ul>
      </div>
      <button class="bg-orange-300 text-black py-3 px-6 rounded-full text-lg hover:bg-orange-400 mt-6 transition-all">Finalizar Pedido</button>
    </div>

    <!-- Main Content -->
    <div class="bg-orange-100 w-full p-8 lg:ml-1/4 flex flex-col overflow-y-auto">
      <div class="fixed top-0 left-0 w-full z-20 bg-orange-300 shadow-lg py-4">
        <div class="flex justify-between items-center">
          <button onclick="toggleSidebar()" class="lg:hidden text-3xl font-bold text-black hover:text-gray-600">
            &#9776;
          </button>
          <h2 class="text-3xl font-semibold text-center">Cardápio</h2>
          <div></div>
        </div>
      </div>

      <!-- Pratos -->
      <h3 id="pratos" class="text-3xl font-semibold mb-8 mt-20">Pratos</h3>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="bg-white shadow-lg rounded-lg overflow-hidden cursor-pointer hover:scale-105 transition-all" onclick="showDetails('Prato 1', 'Descrição completa do prato 1 aqui.')">
          <img src="https://via.placeholder.com/300" alt="Prato 1" class="w-full h-32 object-cover">
          <div class="p-4">
            <h4 class="text-xl font-semibold">Prato 1</h4>
            <p class="text-gray-500 mt-2">Descrição breve do prato.</p>
          </div>
        </div>
        <div class="bg-white shadow-lg rounded-lg overflow-hidden cursor-pointer hover:scale-105 transition-all" onclick="showDetails('Prato 2', 'Descrição completa do prato 2 aqui.')">
          <img src="https://via.placeholder.com/300" alt="Prato 2" class="w-full h-32 object-cover">
          <div class="p-4">
            <h4 class="text-xl font-semibold">Prato 2</h4>
            <p class="text-gray-500 mt-2">Descrição breve do prato.</p>
          </div>
        </div>
        <div class="bg-white shadow-lg rounded-lg overflow-hidden cursor-pointer hover:scale-105 transition-all" onclick="showDetails('Prato 3', 'Descrição completa do prato 3 aqui.')">
          <img src="https://via.placeholder.com/300" alt="Prato 3" class="w-full h-32 object-cover">
          <div class="p-4">
            <h4 class="text-xl font-semibold">Prato 3</h4>
            <p class="text-gray-500 mt-2">Descrição breve do prato.</p>
          </div>
        </div>
      </div>

      <!-- Sobremesas -->
      <h3 id="sobremesas" class="text-3xl font-semibold mt-12 mb-8">Sobremesas</h3>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="bg-white shadow-lg rounded-lg overflow-hidden cursor-pointer hover:scale-105 transition-all" onclick="showDetails('Sobremesa 1', 'Descrição completa da sobremesa 1 aqui.')">
          <img src="https://via.placeholder.com/300" alt="Sobremesa 1" class="w-full h-32 object-cover">
          <div class="p-4">
            <h4 class="text-xl font-semibold">Sobremesa 1</h4>
            <p class="text-gray-500 mt-2">Descrição breve da sobremesa.</p>
          </div>
        </div>
        <div class="bg-white shadow-lg rounded-lg overflow-hidden cursor-pointer hover:scale-105 transition-all" onclick="showDetails('Sobremesa 2', 'Descrição completa da sobremesa 2 aqui.')">
          <img src="https://via.placeholder.com/300" alt="Sobremesa 2" class="w-full h-32 object-cover">
          <div class="p-4">
            <h4 class="text-xl font-semibold">Sobremesa 2</h4>
            <p class="text-gray-500 mt-2">Descrição breve da sobremesa.</p>
          </div>
        </div>
        <div class="bg-white shadow-lg rounded-lg overflow-hidden cursor-pointer hover:scale-105 transition-all" onclick="showDetails('Sobremesa 3', 'Descrição completa da sobremesa 3 aqui.')">
          <img src="https://via.placeholder.com/300" alt="Sobremesa 3" class="w-full h-32 object-cover">
          <div class="p-4">
            <h4 class="text-xl font-semibold">Sobremesa 3</h4>
            <p class="text-gray-500 mt-2">Descrição breve da sobremesa.</p>
          </div>
        </div>
      </div>

      <!-- Bebidas -->
      <h3 id="bebidas" class="text-3xl font-semibold mt-12 mb-8">Bebidas</h3>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="bg-white shadow-lg rounded-lg overflow-hidden cursor-pointer hover:scale-105 transition-all" onclick="showDetails('Bebida 1', 'Descrição completa da bebida 1 aqui.')">
          <img src="https://via.placeholder.com/300" alt="Bebida 1" class="w-full h-32 object-cover">
          <div class="p-4">
            <h4 class="text-xl font-semibold">Bebida 1</h4>
            <p class="text-gray-500 mt-2">Descrição breve da bebida.</p>
          </div>
        </div>
        <div class="bg-white shadow-lg rounded-lg overflow-hidden cursor-pointer hover:scale-105 transition-all" onclick="showDetails('Bebida 2', 'Descrição completa da bebida 2 aqui.')">
          <img src="https://via.placeholder.com/300" alt="Bebida 2" class="w-full h-32 object-cover">
          <div class="p-4">
            <h4 class="text-xl font-semibold">Bebida 2</h4>
            <p class="text-gray-500 mt-2">Descrição breve da bebida.</p>
          </div>
        </div>
        <div class="bg-white shadow-lg rounded-lg overflow-hidden cursor-pointer hover:scale-105 transition-all" onclick="showDetails('Bebida 3', 'Descrição completa da bebida 3 aqui.')">
          <img src="https://via.placeholder.com/300" alt="Bebida 3" class="w-full h-32 object-cover">
          <div class="p-4">
            <h4 class="text-xl font-semibold">Bebida 3</h4>
            <p class="text-gray-500 mt-2">Descrição breve da bebida.</p>
          </div>
        </div>
      </div>
    </div>

    <!-- sidebar do detalhe dos itens -->
    <div id="details-sidebar" class="fixed top-0 right-0 w-full lg:w-96 h-full bg-white shadow-lg transform translate-x-full transition-all duration-300 z-20">
      <div class="flex justify-between p-4">
        <h3 id="item-title" class="text-2xl font-semibold"></h3>
        <button onclick="closeSidebar()" class="text-xl">&times;</button>
      </div>
      <div id="item-description" class="p-4 text-gray-700">
        <p>Detalhes do item aqui...</p>
      </div>
    </div>

  </div>

  <script>
    // sidebar pra celular
    function toggleSidebar() {
      const sidebar = document.getElementById('sidebar');
      sidebar.classList.toggle('lg:hidden');
    }

    // Scroll to the category section
    function scrollToCategory(category) {
      const categoryElement = document.getElementById(category);
      categoryElement.scrollIntoView({ behavior: 'smooth' });
    }

    // mostra os detalhes do produto na sidebar
    function showDetails(title, description) {
      document.getElementById('item-title').innerText = title;
      document.getElementById('item-description').innerText = description;
      const sidebar = document.getElementById('details-sidebar');
      sidebar.classList.remove('translate-x-full');
    }

    function closeSidebar() {
      const sidebar = document.getElementById('details-sidebar');
      sidebar.classList.add('translate-x-full');
    }

    // fecha a sidebar se clicar fora
    document.addEventListener('click', function(event) {
      const sidebar = document.getElementById('details-sidebar');
      if (!sidebar.contains(event.target) && !event.target.closest('.cursor-pointer')) {
        closeSidebar();
      }
    });
  </script>

</body>

</html>
