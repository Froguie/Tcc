<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


if (isset($_POST['id_pedido'])) {
    $id_pedido = $_POST['id_pedido'];

    $sql = "UPDATE pedidos SET status = 'cancelado' WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_pedido);

    if ($stmt->execute()) {
        echo "Pedido cancelado com sucesso.";
    } else {
        echo "Erro ao cancelar o pedido: " . $conn->error;
    }

    $stmt->close();
} else {
    echo "ID do pedido não fornecido.";
}

$conn->close();
?>
