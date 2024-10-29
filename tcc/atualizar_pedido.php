<?php
// Exibe erros para auxiliar no desenvolvimento
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'conexao.php'; // Inclui o arquivo de conexão com o banco de dados

// Obtém o ID da mesa e o status desejado da URL
$mesaId = $_GET['numero'] ?? null;
$status = $_GET['status'] ?? null;

// Verifica se os parâmetros necessários foram passados
if ($mesaId && $status) {
    // Atualiza o status da mesa no banco de dados
    $sql = "UPDATE mesas SET status = ? WHERE numero = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $mesaId);

    // Executa a consulta e verifica o sucesso da operação
    if ($stmt->execute()) {
        echo "Status da mesa atualizado com sucesso.";
    } else {
        echo "Erro ao atualizar o status da mesa: " . $stmt->error;
    }

    // Fecha a instrução preparada
    $stmt->close();
} else {
    echo "ID da mesa ou status não fornecido.";
}

// Fecha a conexão e redireciona para a página inicial
$conn->close();
header("Location: index.php");
exit();
?>
