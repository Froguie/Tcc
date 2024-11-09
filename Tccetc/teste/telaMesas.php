<?php
session_start();
if (isset($_SESSION['nome'])) {
    $usuarioNome = $_SESSION['nome'];
} else {
    $usuarioNome = 'Usuário desconhecido';  // Caso o nome não esteja definido
}

$maxMesas = 12;

// Inicializar as mesas na sessão, se ainda não estiverem definidas
if (!isset($_SESSION['mesas'])) {
    $_SESSION['mesas'] = [
        ['id' => 1, 'title' => '01', 'status' => 'livre'],
        ['id' => 2, 'title' => '02', 'status' => 'livre'],
        ['id' => 3, 'title' => '03', 'status' => 'livre'],
        ['id' => 4, 'title' => '04', 'status' => 'livre'],
        ['id' => 5, 'title' => '05', 'status' => 'livre'],
        ['id' => 6, 'title' => '06', 'status' => 'livre'],
        ['id' => 7, 'title' => '07', 'status' => 'livre'],
        ['id' => 8, 'title' => '08', 'status' => 'livre'],
        ['id' => 9, 'title' => '09', 'status' => 'livre']
    ];
}

// Mensagem de feedback
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'add' && count($_SESSION['mesas']) < $maxMesas) {
            // Adicionar uma nova mesa com ID único
            $newId = !empty($_SESSION['mesas']) ? max(array_column($_SESSION['mesas'], 'id')) + 1 : 1;  // Verifica se o array não está vazio
            $_SESSION['mesas'][] = ['id' => $newId, 'title' => str_pad($newId, 2, '0', STR_PAD_LEFT), 'status' => 'livre'];
            $message = "Mesa adicionada com sucesso!";
        } elseif ($_POST['action'] === 'delete' && isset($_POST['mesa_id'])) {
            // Remover a mesa selecionada
            $mesaId = $_POST['mesa_id'];
            $_SESSION['mesas'] = array_filter($_SESSION['mesas'], function ($mesa) use ($mesaId) {
                return $mesa['id'] != $mesaId;
            });
            $_SESSION['mesas'] = array_values($_SESSION['mesas']); // Reindexa o array
            $message = "Mesa excluída com sucesso!";
        } elseif ($_POST['action'] === 'toggle_status' && isset($_POST['mesa_id'])) {
            // Alternar o status da mesa (livre/ocupada)
            foreach ($_SESSION['mesas'] as &$mesa) {
                if ($mesa['id'] == $_POST['mesa_id']) {
                    // Alterna entre 'livre' e 'ocupada'
                    $mesa['status'] = ($mesa['status'] === 'livre') ? 'ocupada' : 'livre';
                    $message = "Status da mesa alterado!";
                    break;
                }
            }
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
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet">
    <style>
        /* Estilo do popup (modal) */
        .popup {
            position: fixed;
            top: 20px;
            /* Distância do topo */
            right: 20px;
            /* Distância da borda direita */
            background-color: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 15px;
            border-radius: 8px;
            display: none;
            /* Inicialmente oculto */
            z-index: 1000;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
        }

        .transition-all {
            transition: all 0.3s ease;
        }

        .status-livre {
            background-color: #48BB78;
            /* Verde */
        }

        .status-ocupada {
            background-color: #F56565;
            /* Vermelho */
        }

        .status-livre:hover {
            background-color: #38A169;
        }

        .status-ocupada:hover {
            background-color: #C53030;
        }
    </style>
</head>

<body class="bg-orange-300 flex flex-col h-screen">

    <nav class="bg-black border-gray-200">
        <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
            <a class="flex items-center space-x-3">
                <div class="rounded-full bg-orange-300 h-8 w-8"></div>
                <span class="self-center text-2xl font-semibold text-white whitespace-nowrap">
                    Olá, <?php echo $_SESSION['nome']; ?>
                </span>
            </a>
            <div class="hidden w-full md:block md:w-auto" id="navbar-default">
                <ul
                    class="font-medium bg-black flex flex-col p-4 md:p-0 mt-4 border border-gray-100 rounded-lg md:flex-row md:space-x-8 rtl:space-x-reverse md:mt-0 md:border-0">
                    <li><a href="caixa.php"
                            class="block py-2 px-3 text-white rounded md:p-0 hover:text-orange-300">Caixa</a></li>
                    <li><a href="telaMesas.php"
                            class="block py-2 px-3 text-white rounded md:p-0 hover:text-orange-300">Mesas</a></li>
                    <li><a href="produtos.php"
                            class="block py-2 px-3 text-white rounded md:p-0 hover:text-orange-300">Produtos</a></li>
                    <li><a href="logout.php"
                            class="block py-2 px-3 text-white rounded md:p-0 hover:text-orange-300">Sair</a></li>
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
                    <button type="submit" class="bg-black hover:bg-green-600 text-white font-bold py-2 px-4 rounded"
                        <?php if (count($_SESSION['mesas']) >= $maxMesas)
                            echo 'disabled'; ?>>
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
                    <form method="POST" class="inline-block">
                        <input type="hidden" name="action" value="toggle_status">
                        <input type="hidden" name="mesa_id" value="<?= $mesa['id'] ?>">
                        <button type="submit" class="mx-auto py-10 w-1/2 rounded-lg shadow-md text-xl font-bold 
                                transition-all <?= $mesa['status'] === 'livre' ? 'status-livre' : 'status-ocupada' ?>">
                            <?= $mesa['title'] ?>
                        </button>
                    </form>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Popup de Mensagem -->
        <div id="popup" class="popup">
            <span id="popup-message"></span>
        </div>

        <!-- Status das Mesas -->
        <div class="w-1/4 flex flex-col space-y-4 ml-6">
            <div class="bg-black text-white p-6 rounded-lg shadow-md text-center">
                <h3 class="text-xl font-bold">Mesas Livres</h3>
                <?php foreach ($_SESSION['mesas'] as $mesa): ?>
                    <?php if ($mesa['status'] === 'livre'): ?>
                        <p class="mt-2"><?= $mesa['title'] ?></p>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
            <div class="bg-black text-white p-6 rounded-lg shadow-md text-center">
                <h3 class="text-xl font-bold">Mesas Ocupadas</h3>
                <?php foreach ($_SESSION['mesas'] as $mesa): ?>
                    <?php if ($mesa['status'] === 'ocupada'): ?>
                        <p class="mt-2"><?= $mesa['title'] ?></p>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <script>
        // Exibir o popup com a mensagem
        <?php if ($message): ?>
            document.getElementById('popup-message').innerText = "<?= $message; ?>";
            var popup = document.getElementById('popup');
            popup.style.display = 'block'; // Exibe o popup

            // Esconde o popup após 3 segundos
            setTimeout(function () {
                popup.style.display = 'none';
            }, 3000);
        <?php endif; ?>
    </script>
</body>

</html>