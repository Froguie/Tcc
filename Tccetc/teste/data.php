<?php
include("../backend/conexao.php");

//pegar as mesas e os pedidos do banco
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
                         WHERE p.codMesa = " . $mesa['codMesa'];

        $resultPedidos = $conexao->query($queryPedidos);
        $pedidos = [];

        while ($pedido = $resultPedidos->fetch_assoc()) {
            $pedidos[] = $pedido;
        }

        // Adiciona os pedidos à mesa
        $mesa['pedido'] = $pedidos;

        // Adiciona a mesa ao array de mesas
        $mesas[] = $mesa;
    }

    return $mesas;
}

function adicionarProduto($nome, $descricao, $preco)
{
    // Código para adicionar o produto ao banco de dados
    // Exemplo de inserção (ajuste conforme seu banco de dados):
    $conexao = new mysqli("localhost", "root", "", "tcc");
    if ($conexao->connect_error) {
        die("Conexão falhou: " . $conexao->connect_error);
    }

    $sql = "INSERT INTO produtos (nome, descricao, preco) VALUES ('$nome', '$descricao', '$preco')";

    if ($conexao->query($sql) === TRUE) {
        echo "Novo produto adicionado com sucesso!";
    } else {
        echo "Erro ao adicionar produto: " . $conexao->error;
    }

    $conexao->close();
}

// Função para obter todos os produtos do banco de dados
function getProdutos() {
    include("../backend/conexao.php");
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

    $conexao->close(); // Fecha a conexão com o banco de dados
    return $produtos; // Retorna o array com os produtos
}

?>