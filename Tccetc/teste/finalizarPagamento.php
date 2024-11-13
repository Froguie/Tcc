<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finalizar Pedido</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-900 flex items-center justify-center min-h-screen">
    <div class="bg-gray-800 rounded-lg p-8 shadow-lg w-full max-w-2xl text-white">
        <!-- Título e Número da Mesa -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold">Finalizar pedido</h2>
            <span class="text-sm">Mesa 0</span>
        </div>
        
        <!-- Itens do Pedido -->
        <div class="text-base mb-6 space-y-4">
            <div class="flex justify-between border-b border-gray-700 pb-2">
                <span>Macarrão ao sugo</span>
                <span>01</span>
                <span>R$22,00</span>
            </div>
            <div class="flex justify-between border-b border-gray-700 pb-2">
                <span>Guaraná lata 350ml</span>
                <span>01</span>
                <span>R$9,00</span>
            </div>
        </div>
        
        <!-- Total -->
        <div class="flex justify-end text-lg border-t border-gray-700 pt-4 mb-6">
            <span class="font-semibold">Total: R$31,00</span>
        </div>
        
        <!-- Botão Pagar e Voltar -->
        <div class="flex justify-between">
            <a href="#" class="text-yellow-500 hover:underline text-lg">Voltar</a>
            <button class="bg-yellow-500 text-gray-900 font-bold py-2 px-10 rounded hover:bg-yellow-600 transition text-lg">
                Pagar
            </button>
        </div>
    </div>
</body>
</html>