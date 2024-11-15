<?php
include("../backend/conexao.php");

// Função para pegar todas as mesas e seus pedidos
function getMesas()
{
    global $conexao;
    $mesas = [];
    $queryMesas = "SELECT numero, statusMesa FROM mesa ORDER BY numero ASC";
    $resultMesas = $conexao->query($queryMesas);

    if ($resultMesas && $resultMesas->num_rows > 0) {
        while ($mesa = $resultMesas->fetch_assoc()) {
            $numeroMesa = $mesa['numero'];

            // Consulta para pegar os pedidos dessa mesa
            $queryPedidos = "
                SELECT p.nomeProduto AS item, p.precoProduto AS preco, pd.quantidade 
                FROM pedido pd
                INNER JOIN produto p ON pd.codProPe = p.codProduto
                WHERE pd.numeroMesa = $numeroMesa AND pd.statusPedido = 'aberto'
            ";
            $resultPedidos = $conexao->query($queryPedidos);

            $pedidoMesa = [];
            if ($resultPedidos && $resultPedidos->num_rows > 0) {
                while ($pedido = $resultPedidos->fetch_assoc()) {
                    $pedidoMesa[] = [
                        "item" => $pedido['item'],
                        "preco" => $pedido['preco'],
                        "quantidade" => $pedido['quantidade']
                    ];
                }
            }

            $mesas[] = [
                "numero" => $numeroMesa,
                "pedido" => $pedidoMesa
            ];
        }
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