<template>
  <div class="fixed top-4 right-4 flex flex-col gap-4 z-20">
    <div class="relative">
      <transition-group name="fade-slide" tag="div" enter-active-class="transition-all duration-500 ease-out"
        leave-active-class="transition-all duration-500 ease-in" enter-from-class="opacity-0 -translate-y-4"
        enter-to-class="opacity-100 translate-y-0" leave-from-class="opacity-100 translate-y-0"
        leave-to-class="opacity-0">
        <div v-for="notif in notifications" :key="notif.id"
          class="absolute top-0 right-0 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 border dark:border-gray-700/90 p-4 rounded-md shadow-2xl">
          <div class="flex items-start gap-3">
            <div class="shrink-0">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" data-slot="icon"
                aria-hidden="true" class="size-6 text-green-500">
                <path d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" stroke-linecap="round"
                  stroke-linejoin="round"></path>
              </svg>
            </div>
            <div class="pt-1">
              <p class="text-sm">{{ notif.message }}</p>
              <p class="text-nowrap text-sm font-light text-gray-800 dark:text-gray-400">{{ notif.message_2 }}</p>
            </div>
            <div class="shrink-0">
              <button
                class="flex items-center text-gray-300 hover:text-gray-900 dark:text-gray-700 dark:hover:text-gray-100"
                @click="closeNotification(notif.id)">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                  stroke="currentColor" class="size-6">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
              </button>
            </div>
          </div>
        </div>
      </transition-group>
    </div>
  </div>
</template>

<script setup>

import { ref, onMounted, onUnmounted } from 'vue'
import emitter from '../../composables/eventBus.js'

const notifications = ref([])

function showNotification({ type, message, message_2 }) {
  const id = Date.now() + Math.random()
  notifications.value.push({ id, type, message, message_2 })
  setTimeout(() => {
    closeNotification(id)
  }, 10000)
}

function closeNotification(id) {
  notifications.value = notifications.value.filter(notif => notif.id !== id)
}

onMounted(() => {
  emitter.on('notify', showNotification)
})
onUnmounted(() => {
  emitter.off('notify', showNotification)
})

</script>
