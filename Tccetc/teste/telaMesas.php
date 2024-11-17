<?php
session_start();
include("../backend/conexao.php");

$maxmesa = 12;
$message = '';

// Lógica de processamento do formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        // Adicionar mesa apenas se não existir
        if ($_POST['action'] === 'add' && count(getmesa()) < $maxmesa) {
            // Verifica se já existem mesas
            $result = $conexao->query("SELECT MAX(numero) as max_numero FROM mesa");
            $row = $result->fetch_assoc();
            $nextNumero = $row['max_numero'] ? $row['max_numero'] + 1 : 1;

            if ($row['total'] < $maxmesa) {
                // Adicionando uma nova mesa
                $stmt = $conexao->prepare("INSERT INTO mesa (nomeMesa, statusMesa, numero) VALUES (?, ?, ?)");
                $newId = count(getmesa()) + 1;
                $nomeMesa = "Mesa " . $nextNumero; // Gera nomeMesa como "01", "02", ...
                $statusMesa = 'livre';
                $stmt->bind_param("ssi", $nomeMesa, $statusMesa, $nextNumero);
                $stmt->execute();
                $stmt->close();
                $message = "Mesa adicionada com sucesso!";

                // Redireciona para evitar reenvio do formulário
                header("Location: telaMesas.php");
                exit;
            }
        }
        // Excluir mesa
        elseif ($_POST['action'] === 'delete' && isset($_POST['mesa_id'])) {
            $mesaId = $_POST['mesa_id'];
            $stmt = $conexao->prepare("DELETE FROM mesa WHERE codMesa = ?");
            $stmt->bind_param("i", $mesaId);
            $stmt->execute();
            $stmt->close();
            $message = "Mesa excluída com sucesso!";
        }
        // Alternar status da mesa (de livre para ocupada e vice-versa)
        elseif ($_POST['action'] === 'toggle_status' && isset($_POST['mesa_id'])) {
            $mesaId = $_POST['mesa_id'];
            $stmt = $conexao->prepare("SELECT statusMesa FROM mesa WHERE codMesa = ?");
            $stmt->bind_param("i", $mesaId);
            $stmt->execute();
            $stmt->bind_result($currentStatus);
            $stmt->fetch();
            $stmt->close();

            $newStatus = ($currentStatus === 'livre') ? 'ocupada' : 'livre'; // Alterna entre 'livre' e 'ocupada'
            $stmt = $conexao->prepare("UPDATE mesa SET statusMesa = ? WHERE codMesa = ?");
            $stmt->bind_param("si", $newStatus, $mesaId);
            $stmt->execute();
            $stmt->close();
            $message = "Status da mesa alterado!";

            header("Location: telaMesas.php");
            exit;
        }
    }
}

// Função para obter todas as mesas do banco de dados
function getmesa($status = null)
{
    global $conexao;
    $mesa = [];
    $query = "SELECT * FROM mesa";
    if ($status) {
        $query .= " WHERE statusMesa = '$status'";
    }
    $result = $conexao->query($query);
    while ($row = $result->fetch_assoc()) {
        $mesa[] = $row;
    }
    return $mesa;
}

$livreMesas = getmesa('livre');
$ocupadaMesas = getmesa('ocupada');
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mesas - TastyX</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet">
    <style>
        /* Estilo do popup (modal) no canto superior direito */
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

        .popup.show {
            display: block;
        }
    </style>
</head>

<body class="bg-orange-300 flex flex-col h-screen">
    <!--NAVBAR-->
    <nav class="bg-black border-gray-200">
        <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
            <a class="flex items-center space-x-3">
                <div class="rounded-full bg-orange-300 h-8 w-8"></div>
                <span class="self-center text-xl md:text-2xl font-semibold text-white whitespace-nowrap">
                    Olá,
                    <?php echo isset($_SESSION['usuario']) ? $_SESSION['usuario']['nome'] : 'Usuário desconhecido'; ?>
                </span>
            </a>

            <!-- Menu Burger -->
            <button id="menu-toggle" class="block md:hidden text-white focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7">
                    </path>
                </svg>
            </button>

            <!-- Links da Navbar -->
            <div id="menu"
                class="hidden w-full md:flex md:w-auto flex-col md:flex-row items-center md:space-x-4 mt-4 md:mt-0">
                <a href="caixa.php"
                    class="text-white hover:text-black hover:underline transition hover:bg-orange-300 px-3 md:px-4 py-2 rounded-md">Caixa</a>
                <a href="#"
                    class="text-orange-300 underline hover:text-black hover:underline transition hover:bg-orange-300 px-3 md:px-4 py-2 rounded-md">Mesas</a>
                <a href="produtos.php"
                    class="text-white hover:text-black hover:underline transition hover:bg-orange-300 px-3 md:px-4 py-2 rounded-md">Produto</a>

                <!-- Botão de Logout -->
                <a href="../logout.php" class="text-white bg-red-600 hover:bg-red-700 px-3 md:px-4 py-2 rounded-md">
                    Sair
                </a>
            </div>
        </div>
    </nav>


    <!-- Área Principal -->
    <div class="container mx-auto p-6 flex">
        <!-- Mensagem de Feedback -->
        <div id="popup" class="popup <?php echo $message ? 'show' : ''; ?>">
            <span id="popup-message"><?= $message; ?></span>
        </div>

        <!-- Grid de mesa e Botões -->
        <div class="w-3/4">
            <div class="flex justify-between mb-4">
                <!-- Botão para adicionar mesa -->
                <form method="POST" class="inline">
                    <input type="hidden" name="action" value="add">
                    <button type="submit" class="bg-black hover:bg-green-600 text-white font-bold py-2 px-4 rounded"
                        <?php if (count(getmesa()) >= $maxmesa)
                            echo 'disabled'; ?>>
                        Adicionar Mesa
                    </button>
                </form>

                <!-- Formulário para excluir mesa -->
                <form method="POST" class="inline">
                    <input type="hidden" name="action" value="delete">
                    <select name="mesa_id" class="bg-black text-white border border-gray-300 rounded px-2 py-1 mr-2">
                        <?php foreach (getmesa() as $mesa): ?>
                            <option value="<?= $mesa['codMesa'] ?>"><?= $mesa['nomeMesa'] ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit" class="bg-black hover:bg-red-600 text-white font-bold py-2 px-4 rounded">
                        Excluir Mesa
                    </button>
                </form>
            </div>

            <!-- Grid de mesa (4x4) -->
            <div class="grid grid-cols-4 gap-4 bg-black p-10 rounded-lg">
                <?php foreach (getmesa() as $mesa): ?>
                    <form method="POST" class="inline-block">
                        <input type="hidden" name="action" value="toggle_status">
                        <input type="hidden" name="mesa_id" value="<?= $mesa['codMesa'] ?>">
                        <button type="submit" class="mx-auto py-10 w-1/2 rounded-lg shadow-md text-xl font-bold 
                                <?php echo $mesa['statusMesa'] === 'livre' ? 'bg-green-500' : 'bg-red-500'; ?>">
                            <?= $mesa['nomeMesa'] ?>
                        </button>
                    </form>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Painel Lateral à Esquerda -->
        <div class="w-1/4 ml-6 flex flex-col space-y-4">
            <!-- Mesas Livres -->
            <div class="bg-black text-white p-6 rounded-lg shadow-md text-center">
                <h3 class="text-xl font-bold">Mesas Livres</h3>
                <?php foreach ($livreMesas as $mesa): ?>
                    <p class="mt-2"><?= $mesa['nomeMesa']; ?></p>
                <?php endforeach; ?>
            </div>

            <!-- Mesas Ocupadas -->
            <div class="bg-black text-white p-6 rounded-lg shadow-md text-center">
                <h3 class="text-xl font-bold">Mesas Ocupadas</h3>
                <?php foreach ($ocupadaMesas as $mesa): ?>
                    <p class="mt-2"><?= $mesa['nomeMesa']; ?></p>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <script>
        // Exibir o popup com a mensagem
        <?php if ($message): ?>
            document.getElementById('popup-message').innerText = "<?= $message; ?>";
            var popup = document.getElementById('popup');
            setTimeout(function () {
                popup.classList.remove('show');
            }, 3000); // Esconde o popup após 3 segundos
        <?php endif; ?>
    </script>
</body>

</html>