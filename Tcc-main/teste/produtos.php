<?php

include 'config.php'; // inclui o arquivo de configuração para conectar ao banco de dados

$sql = "SELECT * FROM Produto";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header class="navbar">
    <h1>Lista de Produtos</h1>
</header>

<div class="container">
    <h2>Produtos Disponíveis</h2>
    <table>
        <tr>
            <th>Nome do Produto</th>
            <th>Descrição</th>
            <th>Preço</th>
        </tr>
        <?php
        // verifica se há produtos e exibe na tabela
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["nomeProduto"] . "</td>";
                echo "<td>" . $row["descricaoProduto"] . "</td>";
                echo "<td>R$ " . number_format($row["precoProduto"], 2, ',', '.') . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='3'>Nenhum produto encontrado.</td></tr>";
        }
        ?>
    </table>

    <a href="cadastro_produtos.php">Adicionar Novo Produto</a>
</div>

</body>
</html>

<?php
$conn->close(); // fecha a conexão com o banco de dados
?>
