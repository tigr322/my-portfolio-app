<template>
  <div>
    <!-- Hero -->
    <section class="text-center py-24 px-4 border-b border-gray-800">
      <h2 class="text-4xl md:text-5xl font-bold mb-4">Привет, я Тигран 👋</h2>
      <p class="text-lg md:text-xl text-gray-400 max-w-2xl mx-auto">
        FullStack-разработчик из России. Я создаю Laravel/Vue-приложения, Telegram-ботов, REST API, парсеры и админки.
      </p>
      <div class="mt-8 space-x-4">
        <a href="#contact" class="px-6 py-3 bg-blue-600 rounded hover:bg-blue-700 transition">Связаться</a>
        <a href="#projects" class="px-6 py-3 border border-gray-400 rounded hover:border-white transition">Смотреть проекты</a>
      </div>
    </section>

    <!-- Проекты -->
    <section id="projects" class="max-w-6xl mx-auto py-24 px-4">
      <h3 class="text-3xl font-semibold mb-12 text-center">Проекты</h3>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <div class="bg-gray-900 border border-gray-800 p-6 rounded-lg shadow-md hover:shadow-lg transition">
          <h4 class="text-xl font-bold mb-2">⚙️ Конфигуратор ПК</h4>
          <p class="text-gray-400 text-sm mb-4">Laravel + blade-шаблоны + RoachPHP + CronJob, парсинг DNS/Regard, фильтрация, совместимость, обновление цен и тд</p>
          <a href="https://www.pc-configurator.ru/" class="text-blue-400 hover:underline">Подробнее</a>
        </div>
        <div class="bg-gray-900 border border-gray-800 p-6 rounded-lg shadow-md hover:shadow-lg transition">
          <h4 class="text-xl font-bold mb-2">💱 GoldShineTrade</h4>
          <p class="text-gray-400 text-sm mb-4">Laravel + Vue + Tailwind. Сделки, чат, заморозка средств, отзывы, фильтры.</p>
          <a href="https://goldshinetrade.ru/" class="text-blue-400 hover:underline">Подробнее</a>
        </div>
        <div class="bg-gray-900 border border-gray-800 p-6 rounded-lg shadow-md hover:shadow-lg transition">
          <h4 class="text-xl font-bold mb-2">🤖 Telegram-боты</h4>
          <p class="text-gray-400 text-sm mb-4">Интеграции с API, уведомления, автодействия.</p>
          <a href="#" class="text-blue-400 hover:underline">Подробнее</a>
        </div>
      </div>
    </section>

    <!-- Обо мне -->
    <section id="about" class="bg-gray-900 py-24 px-4">
      <div class="max-w-4xl mx-auto text-center">
        <h3 class="text-3xl font-semibold mb-6">Обо мне</h3>
        <p class="text-gray-400 leading-relaxed text-lg">
          Laravel, Vue, Tailwind, Docker, WordPress, Bitrix. Парсеры, боты, админки, сделки. Чистый код и результат.
        </p>
      </div>
    </section>

    <!-- Контакты -->
    <section id="contact" class="max-w-4xl mx-auto py-24 px-4">
      <h3 class="text-3xl font-semibold mb-6 text-center">Контакты</h3>
      <ul class="text-gray-400 text-lg space-y-3 text-center">
        <li>📬 Email: <a href="mailto:tigran05012002@mail.ru" class="underline">tigran05012002@mail.ru</a></li>
        <li>💬 Telegram: <a href="https://t.me/tigranadamyan" target="_blank" class="underline">@tigranadamyan</a></li>
        <li>💻 GitHub: <a href="https://github.com/tigr322" target="_blank" class="underline">github.com/tigr322</a></li>
      </ul>
    </section>
  </div>
</template>

<script>
export default { name: 'Home' }
</script>
