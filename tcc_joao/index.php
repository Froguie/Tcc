<?php
session_start();

// Inicializar as mesas na sessão, se ainda não estiverem definidas
if (!isset($_SESSION['mesas'])) {
    $_SESSION['mesas'] = [
        ['id' => 1, 'title' => '01'],
        ['id' => 2, 'title' => '02'],
        ['id' => 3, 'title' => '03'],
        ['id' => 4, 'title' => '04'],
        ['id' => 5, 'title' => '05'],
        ['id' => 6, 'title' => '06'],
        ['id' => 7, 'title' => '07'],
        ['id' => 8, 'title' => '08'],
        ['id' => 9, 'title' => '09']
    ];
}

// Funções para adicionar ou excluir mesas
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'add') {
            // Adicionar uma nova mesa com um ID incrementado
            $newId = count($_SESSION['mesas']) + 1;
            $_SESSION['mesas'][] = ['id' => $newId, 'title' => str_pad($newId, 2, '0', STR_PAD_LEFT)];
        } elseif ($_POST['action'] === 'delete') {
            // Remover a última mesa se existir
            array_pop($_SESSION['mesas']);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-yellow-400 font-sans">

  <!-- Barra de Navegação -->
  <div class="bg-gray-800 text-white flex justify-between items-center p-4">
    <div class="flex items-center">
        <span class="text-yellow-400 text-2xl font-bold mr-2">🟠</span>
        <span class="text-xl font-semibold">Admin</span>
    </div>
    <div class="flex space-x-6">
        <a href="#" class="hover:underline">Caixa</a>
        <a href="#" class="text-yellow-400 font-semibold hover:underline">Mesas</a>
        <a href="#" class="hover:underline">Produtos</a>
    </div>
  </div>

  <!-- Área Principal -->
  <div class="container mx-auto p-6 flex">
    <!-- Grid de Mesas e Botões -->
    <div class="w-3/4">
      <!-- Botões de Adicionar e Excluir Mesas -->
      <div class="flex justify-between mb-4">
        <!-- Formulário para adicionar mesa -->
        <form method="POST" class="inline">
          <input type="hidden" name="action" value="add">
          <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">
            Adicionar Mesa
          </button>
        </form>
        <!-- Formulário para excluir mesa -->
        <form method="POST" class="inline">
          <input type="hidden" name="action" value="delete">
          <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded">
            Excluir Mesa
          </button>
        </form>
      </div>
      
      <!-- Grid de Mesas -->
      <div class="grid grid-cols-3 gap-4 bg-yellow-300 p-10 rounded-lg">
        <?php foreach ($_SESSION['mesas'] as $mesa): ?>
            <div class="mx-auto bg-yellow-500 text-center text-xl font-bold py-10 w-1/2 rounded-lg shadow-md aspect-square flex items-center justify-center">
    <?= $mesa['title'] ?>
      </div>

        <?php endforeach; ?>
      </div>
    </div>

    <!-- Status das Mesas -->
    <div class="w-1/4 flex flex-col space-y-4 ml-6">
      <div class="bg-gray-200 p-6 rounded-lg shadow-md text-center">
        <h3 class="text-xl font-bold">Livre</h3>
        <p class="mt-2 text-lg">Mesa 01</p>
      </div>
      <div class="bg-gray-300 p-6 rounded-lg shadow-md text-center">
        <h3 class="text-xl font-bold">Em uso</h3>
        <p class="mt-2 text-lg">Mesa 00</p>
      </div>
    </div>
  </div>
</body>
</html>

