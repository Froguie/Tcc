<?php
 // Certifique-se de que este arquivo tem a conexão com o banco de dados

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Verifica se o formulário foi submetido
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numero = $_POST['numero'] ?? null;
    $status = 'Livre'; // Novo padrão de mesa como "Livre"

    if ($numero) {
        // Insere a nova mesa no banco de dados
        $stmt = $conn->prepare("INSERT INTO mesas (numero, status) VALUES (?, ?)");
        $stmt->bind_param("is", $numero, $status);

        if ($stmt->execute()) {
            echo "Mesa adicionada com sucesso!";
            header("Location: painel.php"); // Redireciona de volta para o painel administrativo
            exit();
        } else {
            echo "Erro ao adicionar mesa: " . $conn->error;
        }

        $stmt->close();
    } else {
        echo "Por favor, insira um número para a mesa.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Adicionar Mesa</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-6">Adicionar Nova Mesa</h1>
        <form action="adicionar_mesa.php" method="POST">
            <div class="mb-4">
                <label for="numero" class="block text-sm font-semibold">Número da Mesa:</label>
                <input type="number" id="numero" name="numero" required class="w-full p-2 border rounded-md">
            </div>
            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">Adicionar Mesa</button>
        </form>
    </div>
</body>
</html>
