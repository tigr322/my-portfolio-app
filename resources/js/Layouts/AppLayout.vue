<template>
  <div class="min-h-screen bg-gray-950 text-white">
    <header class="p-6 border-b border-gray-800">
      <div class="max-w-6xl mx-auto flex justify-between items-center">
        <h1 class="text-2xl font-bold">Tigran Dev</h1>
        <nav class="space-x-4 text-gray-300">
          <a href="/">Главная</a>
          <a href="#projects">Проекты</a>
          <a href="#contact">Контакты</a>
        </nav>
      </div>
    </header>

    <main class="max-w-6xl mx-auto px-4 py-12">
      <slot />
    </main>

    <footer class="py-6 border-t border-gray-800 text-center text-sm text-gray-500">
      © {{ new Date().getFullYear() }} Tigran Dev. Все права защищены.
    </footer>
  </div>
</template>

<script>
export default { name: 'AppLayout' }
</script>
