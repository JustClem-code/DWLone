<template>
  <transition enter-from-class="opacity-0" enter-active-class="transition-opacity duration-500"
    leave-to-class="opacity-0" leave-active-class="transition-opacity duration-500">
    <div v-if="showNotif"
      class="bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 border border-0 dark:border-1 dark:border-gray-700/90 fixed top-4 right-4 z-10 p-4 rounded-md shadow-2xl">
      <div class="flex item-start gap-3">
        <div class="shrink-0">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" data-slot="icon"
            aria-hidden="true" class="size-6 text-green-500">
            <path d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" stroke-linecap="round"
              stroke-linejoin="round"></path>
          </svg>
        </div>
        <div class="pt-1">
          <p class="text-sm">{{ notification.message }}</p>
          <p class="text-sm font-light text-gray-800 dark:text-gray-400">{{ notification.message_2 }}</p>
        </div>
        <div class="shrink-0">
          <button
            class="flex items-center text-gray-300 hover:text-gray-900 dark:text-gray-700 dark:hover:text-gray-100"
            @click="closeNotification">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
              stroke="currentColor" class="size-6">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
      </div>
    </div>
  </transition>
</template>

<script setup>

import { onMounted, onUnmounted, ref } from 'vue'
import emitter from '../../composables/eventBus.js'

const notification = ref(null)
const showNotif = ref(false)

function closeNotification() {
  showNotif.value = false
}

function showNotification({ type, message, message_2 }) {
  closeNotification()
  notification.value = { type, message, message_2 }
  showNotif.value = true

  setTimeout(() => {
    closeNotification()
  }, 3000)
}
onMounted(() => {
  emitter.on('notify', showNotification)
})
onUnmounted(() => {
  emitter.off('notify', showNotification)
})

</script>
