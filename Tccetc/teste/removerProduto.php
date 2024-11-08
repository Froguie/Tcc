<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $produtoId = $_POST['produto_id'];

    // Conecta ao banco de dados
    $conexao = new PDO("mysql:host=localhost;dbname=restaurante", "root", "");

    // Remove o produto
    $sql = "DELETE FROM produto WHERE id = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->execute([$produtoId]);

    header('Location: produtos.php');
    exit;
}
?>
