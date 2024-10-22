<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header class="navbar">
    <h1>Login</h1>
</header>

<div class="container">
    <form action="login_process.php" method="POST">
        <label for="username">Usuário:</label>
        <input type="text" id="username" name="username" required>
        
        <label for="password">Senha:</label>
        <input type="password" id="password" name="password" required>
        
        <input type="submit" value="Entrar">
    </form>

    <p>Não tem uma conta? <a href="cadastro.php">Cadastre-se</a></p>
</div>

</body>
</html>
