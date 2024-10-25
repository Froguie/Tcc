<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <header class="navbar">
        <h1>Login</h1>
    </header>

    <div class="flex justify-center items-center h-screen">
        <div class="bg-white p-6 rounded shadow-md w-1/3">
            <h2 class="text-2xl text-black font-bold mb-4">Login</h2>
            <form action="login_process.php" method="POST" class="space-y-4">
                <div>
                    <label for="email" class="text-black block">Email:</label>
                    <input type="email" name="email" id="email" class="w-full border rounded p-2" required>
                </div>
                <div>
                    <label for="senha" class="text-black block">Senha:</label>
                    <input type="text" name="senha" id="senha" class="w-full border rounded p-2" required>
                </div>
                <button type="submit" class="bg-red-400 text-white px-4 py-2 rounded">Login</button>
            </form>
        </div>
    </div>
</body>
</html>
