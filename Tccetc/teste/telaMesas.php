<?php
session_start();

$maxMesas = 12; // Defina o limite máximo de mesas

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
        if ($_POST['action'] === 'add' && count($_SESSION['mesas']) < $maxMesas) {
            // Adicionar uma nova mesa com um ID incrementado, caso não tenha atingido o limite
            $newId = count($_SESSION['mesas']) + 1;
            $_SESSION['mesas'][] = ['id' => $newId, 'title' => str_pad($newId, 2, '0', STR_PAD_LEFT)];
        } elseif ($_POST['action'] === 'delete' && isset($_POST['mesa_id'])) {
            // Remover a mesa selecionada
            $mesaId = $_POST['mesa_id'];
            $_SESSION['mesas'] = array_filter($_SESSION['mesas'], function ($mesa) use ($mesaId) {
                return $mesa['id'] != $mesaId;
            });
            $_SESSION['mesas'] = array_values($_SESSION['mesas']); // Reindexa o array
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mesas</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="../path/to/flowbite/dist/flowbite.min.js"></script>
</head>
<body class="bg-orange-300 flex flex-col h-screen">
<nav class="bg-black border-gray-200">
        <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
            <a class="flex items-center space-x-3 rtl:space-x-reverse">
                <div class="rounded-full bg-orange-300 h-8 w-8"></div>
                <span class="self-center text-2xl font-semibold text-white whitespace-nowrap">
                     Olá, <?php echo $_SESSION['nome']; ?>
                </span>
            </a>
            <button data-collapse-toggle="navbar-default" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200" aria-controls="navbar-default" aria-expanded="false">
                <span class="sr-only">Abrir menu principal</span>
                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15" />
                </svg>
            </button>
            <div class="hidden w-full md:block md:w-auto" id="navbar-default">
                <ul class="font-medium bg-black flex flex-col p-4 md:p-0 mt-4 border border-gray-100 rounded-lg md:flex-row md:space-x-8 rtl:space-x-reverse md:mt-0 md:border-0">
                    <li>
                        <a href="caixa.php" class="block py-2 px-3 text-white rounded md:p-0 hover:text-orange-300">Caixa</a>
                    </li>
                    <li>
                        <a href="telaMesas.php" class="block py-2 px-3 text-white rounded md:p-0 hover:text-orange-300">Mesas</a>
                    </li>
                    <li>
                        <a href="produtos.php" class="block py-2 px-3 text-white rounded md:p-0 hover:text-orange-300">Produtos</a>
                    </li>
                    <li>
                        <a href="logout.php" class="block py-2 px-3 text-white rounded md:p-0 hover:text-orange-300">Sair</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

  <!-- Área Principal -->
  <div class="container mx-auto p-6 flex">
    <!-- Grid de Mesas e Botões -->
    <div class="w-3/4">
      <!-- Botões de Adicionar e Excluir Mesas -->
      <div class="flex justify-between mb-4">
        <!-- Formulário para adicionar mesa -->
        <form method="POST" class="inline">
          <input type="hidden" name="action" value="add">
          <button type="submit" 
                  class="bg-black hover:bg-green-600 text-white font-bold py-2 px-4 rounded" 
                  <?php if (count($_SESSION['mesas']) >= $maxMesas) echo 'disabled'; ?>>
            Adicionar Mesa
          </button>
        </form>

        <!-- Formulário para excluir mesa selecionada -->
        <form method="POST" class="inline">
          <input type="hidden" name="action" value="delete">
          <select name="mesa_id" class="bg-black text-white border border-gray-300 rounded px-2 py-1 mr-2">
            <?php foreach ($_SESSION['mesas'] as $mesa): ?>
              <option value="<?= $mesa['id'] ?>"><?= $mesa['title'] ?></option>
            <?php endforeach; ?>
          </select>
          <button type="submit" class="bg-black hover:bg-red-600 text-white font-bold py-2 px-4 rounded">
            Excluir Mesa
          </button>
        </form>
      </div>
      
      <!-- Grid de Mesas -->
      <div class="grid grid-cols-3 gap-4 bg-black p-10 rounded-lg">
        <?php foreach ($_SESSION['mesas'] as $mesa): ?>
          <div class="mx-auto bg-orange-300  text-center text-xl font-bold py-10 w-1/2 rounded-lg shadow-md aspect-square flex items-center justify-center">
            <?= $mesa['title'] ?>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Status das Mesas -->
    <div class="w-1/4 flex flex-col space-y-4 ml-6">
      <div class="bg-black hover:bg-green-400 text-white p-6 rounded-lg shadow-md text-center">
        <h3 class="text-xl font-bold">Livre</h3>
        <p class="mt-2 text-lg">Mesa 01</p>
      </div>
      <div class="bg-black hover:bg-red-400 text-white p-6 rounded-lg shadow-md text-center">
        <h3 class="text-xl font-bold">Em uso</h3>
        <p class="mt-2 text-lg">Mesa 00</p>
      </div>
    </div>
  </div>
</body>
</html>