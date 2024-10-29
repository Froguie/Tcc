<?php
 // Certifique-se de que este arquivo tem a conexão com o banco de dados

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Consulta as mesas no banco de dados
$mesas = $conn->query("SELECT * FROM mesas");
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Painel Administrativo - Restaurante</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-6">Painel Administrativo - Restaurante</h1>

        <!-- Botão para adicionar uma nova mesa -->
        <div class="mb-6">
            <a href="adicionar_mesa.php" class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">
                Adicionar Mesa
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <?php while($mesa = $mesas->fetch_assoc()): ?>
                <div class="bg-white shadow-md rounded-lg p-4">
                    <h2 class="text-xl font-semibold">Mesa <?= $mesa["numero"] ?></h2>
                    <p class="text-sm <?= $mesa["status"] == 'Ocupada' ? 'text-red-600' : 'text-green-600' ?>">
                        Status: <?= $mesa["status"] ?>
                    </p>
                    <div class="mt-4">
                        <a href="mesa.php?numero=<?= $mesa['numero'] ?>" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                            Gerenciar
                        </a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>




