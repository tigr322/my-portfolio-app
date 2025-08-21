<script setup>
import { useForm, usePage } from '@inertiajs/vue3'
import { onMounted, onBeforeUnmount, ref,computed } from 'vue'
import { router } from '@inertiajs/vue3'

defineOptions({ name: 'Home' })
const props = defineProps({
  meta: { type: Object, default: () => ({}) },
  projects: { type: Array, default: () => [] },
  user:      { type: Object, default: null },
})
const isAdmin = computed(() => props.user?.is_admin === 1)
const page = usePage()

const selectedImage = ref(null)

function openModalW(src) {
  selectedImage.value = src
}

function closeModalW() {
  selectedImage.value = null
}
console.log('isAdmin', isAdmin.value)

const showModal = ref(false)

const form = useForm({
  name: '',
  distiption: '',
  url: '',
  slogan: '',
  image: null,    
  status: 'draft',
  user_id: '',     
  
})

function openModal() { showModal.value = true }
function closeModal() { showModal.value = false }

function onFile(e) {
  form.image = e.target.files?.[0] ?? null
}

function submit() {
  form.transform((data) => data) // оставим как есть, просто триггерим сериализацию
  form.post('/projects', {
    preserveScroll: true,
    forceFormData: true,
    onFinish: () => form.transform(d => d),
    onSuccess: () => { form.reset(); closeModal() },
  })
}

const deleteProject = (id) => {
  if (confirm('Удалить проект?')) {
    router.post(route('projects.destroy', id), { _method: 'delete' }, {
      preserveScroll: true,
    })
  }
}



// ДОБАВЬ рядом с остальными импортами/реактивностями
const showEditModal = ref(false)
const editForm = useForm({
  id: null,
  name: '',
  distiption: '',
  url: '',
  slogan: '',
  image: null,      
  status: 'draft',
})

// открыть модал редактирования и предзаполнить поля
function openEdit(project) {
  editForm.reset()
  editForm.clearErrors()
  editForm.id = project.id
  editForm.name = project.name ?? ''
  editForm.distiption = project.distiption ?? ''
  editForm.url = project.url ?? ''
  editForm.slogan = project.slogan ?? ''
  
  editForm.status = project.status ?? 'draft'
  showEditModal.value = true
}

function closeEdit() { showEditModal.value = false }

function onEditFile(e) {
  editForm.image = e.target.files?.[0] ?? null
}

// submit PUT /projects/{id} (Inertia)
function submitEdit() {
  const url = route ? route('projects.update', editForm.id) : `/edit/projects/${editForm.id}` // ВАЖНО: ведущий слэш!

  // добавим _method перед отправкой
  editForm.transform((data) => ({ ...data, _method: 'put' }))

  editForm.post(url, {
    preserveScroll: true,
    forceFormData: true,          // нужно для файла
    onSuccess: () => { closeEdit() },
    onFinish: () => {             // вернём transform в исходное состояние
      editForm.transform((d) => d)
    },
  })
}



// UX: закрытие по Esc
function onEsc(e) { if (e.key === 'Escape') closeModal() }
onMounted(() => window.addEventListener('keydown', onEsc))
onBeforeUnmount(() => window.removeEventListener('keydown', onEsc))
</script>
<template>
  <div>
    <!-- Hero -->
    <section class="text-center py-24 px-4 border-b border-gray-800">
      <h2 class="text-4xl md:text-5xl font-bold mb-4">Привет, я Тигран 👋</h2>
      <p class="text-lg md:text-xl text-gray-400 max-w-2xl mx-auto">
        FullStack-разработчик из России. Я создаю Laravel/Vue-приложения, Telegram-ботов, REST API, парсеры и админки.
      </p>
      <div class="mt-8 flex flex-col sm:flex-row sm:justify-center sm:space-x-4 space-y-4 sm:space-y-0">
      <a href="#contact" class="px-6 py-3 bg-blue-600 rounded hover:bg-blue-700 transition text-center">Связаться</a>
      <a href="#projects" class="px-6 py-3 border border-gray-400 rounded hover:border-white transition text-center">Смотреть проекты</a>
    </div>
    </section>
    <!-- Проекты -->
    <section id="projects" class="max-w-6xl mx-auto py-24 px-4">
      <h3 class="text-3xl font-semibold mb-12 text-center">Проекты</h3>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <!-- ↓↓↓ Карточка строится внутри v-for -->
        <article
          v-for="p in props.projects"
          :key="p.id"
          class="bg-gray-900 border border-gray-800 p-6 rounded-lg shadow-md hover:shadow-lg transition"
        >
          <h4 class="text-xl font-bold mb-2">
            {{ p.name }}
          </h4>
          <p class="text-gray-400 text-sm mb-4">
            {{ p.distiption }}
          </p>
          <p v-if="p.slogan" class="text-gray-500 text-sm mb-4">
            {{ p.slogan }}
          </p>
          <a v-if="p.url" :href="p.url" target="_blank" class="text-blue-400 hover:underline">
            Перейти
          </a>
          <picture>
      <img
        v-if="p.image"
        :src="`/storage/projects/${p.image}`"
        class="object-cover rounded-md cursor-pointer"
        @click="openModalW(`/storage/projects/${p.image}`)"
      />
    </picture>
    <div
      v-if="selectedImage"
      class="fixed inset-0 bg-black/70 flex items-center justify-center z-50"
      @click.self="closeModalW"
    >
      <div class="relative">
        <button
          class="absolute -top-8 -right-8 text-white text-3xl font-bold"
          @click="closeModal"
        >
         
        </button>
        <img :src="selectedImage" class="max-h-[90vh] max-w-[90vw] rounded-lg shadow-lg" />
      </div>
    </div>

          <div  v-if="isAdmin" class="mt-4">
   
      <input type="hidden" name="_method" value="DELETE">
      <input type="hidden" name="_token" :value="props.csrf_token">
      <button
      v-if="isAdmin"
      @click="deleteProject(p.id)"
      class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700"
    >
      Удалить
    </button>
    <button
  v-if="isAdmin"
  @click="openEdit(p)"
  class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700"
>
  Редактировать
</button>
<!-- Edit Modal -->
<div
  v-if="showEditModal"
  class="fixed inset-0 z-50 flex items-center justify-center p-4"
  aria-modal="true"
  role="dialog"
>
  <!-- backdrop -->
  <div class="absolute inset-0 bg-black/60" @click="closeEdit"></div>

  <!-- окно -->
  <div class="relative w-full max-w-2xl bg-gray-900 border border-gray-800 rounded-2xl p-6">
    <div class="flex items-center justify-between mb-4">
      <h3 class="text-xl font-semibold">Редактировать проект</h3>
      <button class="text-gray-400 hover:text-white" @click="closeEdit" aria-label="Закрыть">✕</button>
    </div>

    <form @submit.prevent="submitEdit" class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <!-- Название -->
      <div class="md:col-span-2">
        <label class="block text-sm mb-1">Название *</label>
        <input v-model="editForm.name" type="text"
               class="w-full bg-gray-800 rounded px-3 py-2 border border-gray-700 focus:outline-none"
               required />
        <p v-if="editForm.errors.name" class="text-red-400 text-sm mt-1">{{ editForm.errors.name }}</p>
      </div>

      <!-- URL -->
      <div>
        <label class="block text-sm mb-1">URL</label>
        <input v-model="editForm.url" type="url" placeholder="https://..."
               class="w-full bg-gray-800 rounded px-3 py-2 border border-gray-700 focus:outline-none" />
        <p v-if="editForm.errors.url" class="text-red-400 text-sm mt-1">{{ editForm.errors.url }}</p>
      </div>

      <!-- Слоган -->
      <div>
        <label class="block text-sm mb-1">Слоган</label>
        <input v-model="editForm.slogan" type="text"
               class="w-full bg-gray-800 rounded px-3 py-2 border border-gray-700 focus:outline-none" />
        <p v-if="editForm.errors.slogan" class="text-red-400 text-sm mt-1">{{ editForm.errors.slogan }}</p>
      </div>

      <!-- Описание -->
      <div class="md:col-span-2">
        <label class="block text-sm mb-1">Описание</label>
        <textarea v-model="editForm.distiption" rows="4"
                  class="w-full bg-gray-800 rounded px-3 py-2 border border-gray-700 focus:outline-none"></textarea>
        <p v-if="editForm.errors.distiption" class="text-red-400 text-sm mt-1">{{ editForm.errors.distiption }}</p>
      </div>

      <!-- Новое изображение (опционально) -->
      <div>
        <label class="block text-sm mb-1">Новое изображение (необязательно)</label>
        <input type="file" accept="image/*" @change="onEditFile"
               class="w-full text-gray-300 file:mr-3 file:px-3 file:py-2 file:rounded file:border-0 file:bg-gray-700 file:text-white hover:file:bg-gray-600"/>
        <p v-if="editForm.errors.image" class="text-red-400 text-sm mt-1">{{ editForm.errors.image }}</p>
      </div>

      <!-- Статус -->
      <div>
        <label class="block text-sm mb-1">Статус</label>
        <select v-model="editForm.status"
                class="w-full bg-gray-800 rounded px-3 py-2 border border-gray-700 focus:outline-none">
          <option value="draft">Черновик</option>
          <option value="published">Опубликован</option>
        </select>
        <p v-if="editForm.errors.status" class="text-red-400 text-sm mt-1">{{ editForm.errors.status }}</p>
      </div>

      <!-- Кнопки -->
      <div class="md:col-span-2 flex justify-end gap-3 mt-2">
        <button type="button" class="px-4 py-2 rounded border border-gray-700" @click="closeEdit">Отмена</button>
        <button type="submit" :disabled="editForm.processing"
                class="px-4 py-2 rounded bg-blue-600 hover:bg-blue-700 disabled:opacity-50">
          <span v-if="!editForm.processing">Сохранить изменения</span>
          <span v-else>Сохраняю…</span>
        </button>
      </div>
    </form>
  </div>
</div>


    
  </div>
        </article>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
      
        <button
     v-if="isAdmin"
      @click="openModal"
      class="inline-flex items-center gap-2 px-5 py-3 rounded-full bg-blue-600 hover:bg-blue-700 transition shadow-lg"
      title="Добавить проект" 
    >
      <span class="text-xl leading-none">+</span>
      <span class="font-medium">Добавить проект</span>
    </button>
      </div>
      <div
    v-if="showModal"
    class="fixed inset-0 z-50 flex items-center justify-center p-4"
    aria-modal="true"
    role="dialog"
  >
    <!-- backdrop -->
    <div class="absolute inset-0 bg-black/60" @click="closeModal"></div>

    <!-- окно -->
    <div class="relative w-full max-w-2xl bg-gray-900 border border-gray-800 rounded-2xl p-6">
      <div class="flex items-center justify-between mb-4">
        <h3 class="text-xl font-semibold">Новый проект</h3>
        <button class="text-gray-400 hover:text-white" @click="closeModal" aria-label="Закрыть">✕</button>
      </div>

      <form @submit.prevent="submit" class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Название -->
        <div class="md:col-span-2">
          <label class="block text-sm mb-1">Название *</label>
          <input v-model="form.name" type="text"
                 class="w-full bg-gray-800 rounded px-3 py-2 border border-gray-700 focus:outline-none"
                 required />
          <p v-if="form.errors.name" class="text-red-400 text-sm mt-1">{{ form.errors.name }}</p>
        </div>

        <!-- URL -->
        <div>
          <label class="block text-sm mb-1">URL</label>
          <input v-model="form.url" type="url" placeholder="https://..."
                 class="w-full bg-gray-800 rounded px-3 py-2 border border-gray-700 focus:outline-none" />
          <p v-if="form.errors.url" class="text-red-400 text-sm mt-1">{{ form.errors.url }}</p>
        </div>

        <!-- Слоган -->
        <div>
          <label class="block text-sm mb-1">Слоган</label>
          <input v-model="form.slogan" type="text"
                 class="w-full bg-gray-800 rounded px-3 py-2 border border-gray-700 focus:outline-none" />
          <p v-if="form.errors.slogan" class="text-red-400 text-sm mt-1">{{ form.errors.slogan }}</p>
        </div>

        <!-- Описание (distiption — как у тебя в БД) -->
        <div class="md:col-span-2">
          <label class="block text-sm mb-1">Описание</label>
          <textarea v-model="form.distiption" rows="4"
                    class="w-full bg-gray-800 rounded px-3 py-2 border border-gray-700 focus:outline-none"></textarea>
          <p v-if="form.errors.distiption" class="text-red-400 text-sm mt-1">{{ form.errors.distiption }}</p>
        </div>

        <!-- Изображение -->
        <div>
          <label class="block text-sm mb-1">Изображение</label>
          <input type="file" accept="image/*" @change="onFile"
                 class="w-full text-gray-300 file:mr-3 file:px-3 file:py-2 file:rounded file:border-0 file:bg-gray-700 file:text-white hover:file:bg-gray-600"/>
          <p v-if="form.errors.image" class="text-red-400 text-sm mt-1">{{ form.errors.image }}</p>
        </div>

        <!-- Статус -->
        <div>
          <label class="block text-sm mb-1">Статус</label>
          <select v-model="form.status"
                  class="w-full bg-gray-800 rounded px-3 py-2 border border-gray-700 focus:outline-none">
            <option value="draft">Черновик</option>
            <option value="published">Опубликован</option>
          </select>
          <p v-if="form.errors.status" class="text-red-400 text-sm mt-1">{{ form.errors.status }}</p>
        </div>

        <!-- Кнопки -->
        <div class="md:col-span-2 flex justify-end gap-3 mt-2">
          <button type="button" class="px-4 py-2 rounded border border-gray-700" @click="closeModal">Отмена</button>
          <button type="submit" :disabled="form.processing"
                  class="px-4 py-2 rounded bg-blue-600 hover:bg-blue-700 disabled:opacity-50">
            <span v-if="!form.processing">Сохранить</span>
            <span v-else>Сохраняю…</span>
          </button>
        </div>
      </form>
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

    <!-- Контакты g-->
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

