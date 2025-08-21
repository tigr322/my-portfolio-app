<script setup>
import { useForm } from '@inertiajs/vue3'
defineOptions({ name: 'Register' })

const form = useForm({ name: '', password: '', })

function submit() {
  form.post('/register', {
    onSuccess: () => form.reset(),
    onError: (e) => console.error(e),
  })
}
</script>

<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-950 text-white">
    <form @submit.prevent="submit" class="bg-white p-8 rounded shadow-md w-full max-w-sm space-y-6">
      <h2 class="text-2xl font-bold text-center">Регистрация</h2>

      <div>
        <label class="block text-gray-700 mb-1">Имя</label>
        <input v-model="form.name" type="text" required class="bg-gray-950 text-white w-full px-3 py-2 border rounded">
      </div>

      <div>
        <label class="block text-gray-700 mb-1">Пароль</label>
        <input v-model="form.password" type="password" required class="bg-gray-950 text-white w-full px-3 py-2 border rounded">
      </div>

     

      <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded">Зарегистрироваться</button>
    </form>
  </div>
</template>
