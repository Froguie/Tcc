<?php
include("../backend/conexao.php");

// Função para pegar todas as mesas e seus pedidos
function getMesas()
{
    global $conexao;

    // Busca todas as mesas
    $queryMesas = "SELECT * FROM mesa";
    $resultMesas = $conexao->query($queryMesas);

    $mesas = [];

    while ($mesa = $resultMesas->fetch_assoc()) {
        // Para cada mesa, busca seus pedidos
        $queryPedidos = "SELECT p.quantidade, pr.nomeProduto as item, pr.precoProduto as preco 
                         FROM pedido p
                         JOIN produto pr ON p.codProPe = pr.codProduto
                         WHERE p.codMesa = ?";

        $stmt = $conexao->prepare($queryPedidos);
        $stmt->bind_param("i", $mesa['codMesa']); 
        $stmt->execute();
        $resultPedidos = $stmt->get_result();
        $pedidos = [];

        while ($pedido = $resultPedidos->fetch_assoc()) {
            $pedidos[] = $pedido;
        }

        // Adiciona os pedidos à mesa e usa o numero da mesa no array $mesas
        $mesas[] = [
            'numero' => $mesa['numero'], // Número da mesa
            'nome' => $mesa['nomeMesa'], // Nome da mesa
            'status' => $mesa['statusMesa'], // Status da mesa
            'pedido' => $pedidos // Pedidos da mesa
        ];
    }

    return $mesas;
}

function adicionarProduto($nome, $descricao, $preco)
{
    global $conexao;

    // Usando prepared statement para evitar SQL Injection
    $sql = "INSERT INTO produto (nomeProduto, descricaoProduto, precoProduto) VALUES (?, ?, ?)";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("ssd", $nome, $descricao, $preco); // 's' para string, 'd' para double (preço)

    if ($stmt->execute()) {
        echo "Novo produto adicionado com sucesso!";
    } else {
        echo "Erro ao adicionar produto: " . $stmt->error;
    }

    $stmt->close();
}

// Função para obter todos os produtos do banco de dados
function getProdutos()
{
    global $conexao;

    // Consulta SQL para pegar todos os produtos
    $sql = "SELECT * FROM produto";
    $result = $conexao->query($sql);

    // Cria um array para armazenar os produtos
    $produtos = [];

    if ($result->num_rows > 0) {
        // Adiciona cada produto ao array
        while ($row = $result->fetch_assoc()) {
            $produtos[] = $row;
        }
    }

    return $produtos; // Retorna o array com os produtos
}

?>