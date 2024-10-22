<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Produtos</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header class="navbar">
    <h1>Cadastrar Produto</h1>
</header>

<div class="container">
    <form action="cadastro_produtos_process.php" method="POST">
        <label for="product_name">Nome do Produto:</label>
        <input type="text" id="product_name" name="product_name" required>
        
        <label for="category">Categoria:</label>
        <input type="text" id="category" name="category" required>
        
        <label for="description">Descrição:</label>
        <textarea id="description" name="description" rows="4"></textarea>
        
        <input type="submit" value="Cadastrar Produto">
    </form>
</div>

</body>
</html>
