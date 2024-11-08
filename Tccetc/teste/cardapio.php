<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cardápio</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    /* Add smooth scroll effect */
    html {
      scroll-behavior: smooth;
    }
  </style>

</head>
<body class="bg-orange-300">
    <div class="flex h-screen w-screen">
    <div class="bg-black text-white w-1/4 p-4 flex flex-col justify-between h-full">
      <div>
        <button class="text-2xl font-semibold mb-4">X</button>
        <h2 class="text-2xl font-semibold mb-6">Brother's Burger</h2>
        <ul class="space-y-4">
          <li class="text-lg cursor-pointer" onclick="scrollToCategory('pratos')">Pratos</li>
          <li class="text-lg cursor-pointer" onclick="scrollToCategory('sobremesas')">Sobremesas</li>
          <li class="text-lg cursor-pointer" onclick="scrollToCategory('bebidas')">Bebidas</li>
        </ul>
      </div>
      <button class="bg-orange-300 text-black py-2 px-4 rounded-lg text-center text-lg mt-6">Finalizar pedido</button>
    </div>
    
    <!-- Main Content -->
    <div class="bg-orange-300 w-3/4 p-6">
    <h3 id="pratos" class="text-2xl font-semibold mb-6">Pratos</h3>
      <div class="grid grid-cols-3 gap-4">
        <!-- Placeholder items for dishes -->
        <div class="bg-gray-300 w-full h-32 rounded-lg cursor-pointer" onclick="goToDishDetails('Dish 1')">Dish 1</div>
        <div class="bg-gray-300 w-full h-32 rounded-lg cursor-pointer" onclick="goToDishDetails('Dish 2')">Dish 2</div>
        <div class="bg-gray-300 w-full h-32 rounded-lg cursor-pointer" onclick="goToDishDetails('Dish 3')">Dish 3</div>
      </div>

      <!-- Sobremesas Section -->
      <h3 id="sobremesas" class="text-2xl font-semibold mt-10 mb-6">Sobremesas</h3>
      <div class="grid grid-cols-3 gap-4">
        <div class="bg-gray-300 w-full h-32 rounded-lg cursor-pointer" onclick="goToDishDetails('Dessert 1')">Dessert 1</div>
        <div class="bg-gray-300 w-full h-32 rounded-lg cursor-pointer" onclick="goToDishDetails('Dessert 2')">Dessert 2</div>
        <div class="bg-gray-300 w-full h-32 rounded-lg cursor-pointer" onclick="goToDishDetails('Dessert 3')">Dessert 3</div>
      </div>

      <!-- Bebidas Section -->
      <h3 id="bebidas" class="text-2xl font-semibold mt-10 mb-6">Bebidas</h3>
      <div class="grid grid-cols-3 gap-4">
        <div class="bg-gray-300 w-full h-32 rounded-lg cursor-pointer" onclick="goToDishDetails('Drink 1')">Drink 1</div>
        <div class="bg-gray-300 w-full h-32 rounded-lg cursor-pointer" onclick="goToDishDetails('Drink 2')">Drink 2</div>
        <div class="bg-gray-300 w-full h-32 rounded-lg cursor-pointer" onclick="goToDishDetails('Drink 3')">Drink 3</div>
      </div>
    </div>
  
  </div>
  <script>
    // Function to scroll to a specific category section
    function scrollToCategory(categoryId) {
      const element = document.getElementById(categoryId);
      if (element) {
        element.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }
    }

    // Function to go to dish details page
    function goToDishDetails(dishName) {
      // For now, just redirect to a placeholder page (you can replace this with your actual details page)
      // Passing the dish name in the URL as a query parameter
      window.location.href = `details.html?dish=${encodeURIComponent(dishName)}`;
    }
  </script>

</body>
</html>
