<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Início</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header class="navbar">
    <h1>Bem-vindo à Loja</h1>
    <?php if (isset($_SESSION['nome'])): ?>
        <h3>Olá, <?php echo htmlspecialchars($_SESSION['nome']); ?></h3>
        <a href="logout.php">Logout</a>
    <?php else: ?>
        <a href="login.php">Login</a>
    <?php endif; ?>
</header>

<div class="container">
    <h2>Produtos por Categoria</h2>
    <div class="product-category">Bebidas</div>
    <div class="product-category">Cafeteria</div>
    <div class="product-category">Pratos Principais</div>
    <!-- lista de produtos vem aqui debaixo -->
</div>

</body>
</html>
