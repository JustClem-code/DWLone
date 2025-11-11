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
              <CheckCircleIcon color="text-green-500 mt-1"/>
            </div>
            <div class="pt-1">
              <p class="text-sm">{{ notif.message }}</p>
              <p class="text-nowrap text-sm font-light text-gray-800 dark:text-gray-400">{{ notif.message_2 }}</p>
            </div>
            <div class="shrink-0">
              <IconButton @click="closeNotification(notif.id)">
                <CrossIcon />
              </IconButton>
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
import IconButton from './Buttons/IconButton.vue'
import CrossIcon from './Icons/CrossIcon.vue'
import CheckCircleIcon from './Icons/CheckCircleIcon.vue'

const notifications = ref([])

const closeNotification = (id) => {
  notifications.value = notifications.value.filter(notif => notif.id !== id)
}

const showNotification = ({ type, message, message_2 }) => {
  const id = Date.now() + Math.random()
  notifications.value.push({ id, type, message, message_2 })
  setTimeout(() => {
    closeNotification(id)
  }, 3000)
}

onMounted(() => {
  emitter.on('notify', showNotification)
})
onUnmounted(() => {
  emitter.off('notify', showNotification)
})

</script>
