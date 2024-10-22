<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header class="navbar">
    <h1>Cadastro</h1>
</header>

<div class="container">
    <form action="cadastro_process.php" method="POST">
        <label for="username">Usuário:</label>
        <input type="text" id="username" name="username" required>
        
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        
        <label for="password">Senha:</label>
        <input type="password" id="password" name="password" required>
        
        <input type="submit" value="Cadastrar">
    </form>
</div>

</body>
</html>
