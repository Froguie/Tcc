<?php
// pedido.php
include("backend/conexao.php");

// Verifica se há itens selecionados
if (!empty($_POST['pedido'])) {
    $itensSelecionados = $_POST['pedido'];
    $totalPedido = 0;

    // Consulta o preço de cada item no banco de dados
    foreach ($itensSelecionados as $itemNome) {
        $stmt = $conexao->prepare("SELECT preco FROM Cardapio WHERE nome = ?");
        $stmt->bind_param("s", $itemNome);
        $stmt->execute();
        $result = $stmt->get_result();
        $item = $result->fetch_assoc();

        if ($item) {
            $totalPedido += $item['preco'];
        }
    }

    // Inserir o pedido na tabela de Pedidos
    $stmtPedido = $conexao->prepare("INSERT INTO Pedido (total, statusPedido) VALUES (?, 'pronto')");
    $stmtPedido->bind_param("d", $totalPedido);
    $stmtPedido->execute();
    $codPedido = $stmtPedido->insert_id;

    // Associar cada item do pedido à tabela de itens do pedido
    foreach ($itensSelecionados as $itemNome) {
        $stmtItem = $conexao->prepare("INSERT INTO Pedido_Item (codPedido, nomeItem) VALUES (?, ?)");
        $stmtItem->bind_param("is", $codPedido, $itemNome);
        $stmtItem->execute();
    }

    echo "<p>Pedido realizado com sucesso! Total: R$ " . number_format($totalPedido, 2, ',', '.') . "</p>";
} else {
    echo "<p>Nenhum item selecionado.</p>";
}
?>
