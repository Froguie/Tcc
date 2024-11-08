<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pedidoId = $_POST['pedido_id'];
    $metodoPagamento = $_POST['metodo_pagamento'];

    // Conectar ao banco de dados
    $conexao = new PDO("mysql:host=localhost;dbname=restaurante", "root", "");

    // Registrar o pagamento na tabela caixa
    $sql = "INSERT INTO caixa (pedido_id, valor_pago, metodo_pagamento) 
            SELECT id, total, ? FROM pedido WHERE id = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->execute([$metodoPagamento, $pedidoId]);

    // Atualizar o status da mesa para 'livre'
    $sqlMesa = "UPDATE mesa 
                SET status = 'livre' 
                WHERE id = (SELECT mesa_id FROM pedido WHERE id = ?)";
    $stmtMesa = $conexao->prepare($sqlMesa);
    $stmtMesa->execute([$pedidoId]);

    header('Location: caixa.php');
    exit;
}
?>
