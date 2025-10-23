<template>
  <div class="relative inline-block text-left">
    <button type="button" id="menu-button-1"
      class="inline-flex w-8 h-8 items-center justify-center rounded-full text-gray-500 hover:text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500"
      aria-haspopup="true" :aria-expanded="isOpen.toString()" @click="toggleMenu">
      <svg class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
        <path
          d="M10 3a1.5 1.5 0 110 3 1.5 1.5 0 010-3ZM10 8.5a1.5 1.5 0 110 3 1.5 1.5 0 010-3ZM11.5 15.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0Z" />
      </svg>
    </button>

    <OverlayInvisible v-show="isOpen" @click="toggleMenu"/>
    <div v-show="isOpen"
      class="absolute right-0 mt-2 w-44 origin-top-right z-10 rounded-md shadow-lg bg-white dark:bg-gray-800 border border-0 dark:border-1 dark:border-gray-700/90"
      role="menu">
      <ul>
        <li v-for="item in items" :key="item" class="block px-4 py-2 text-sm hover:bg-gray-50 dark:bg-gray-700/50 dark:hover:bg-gray-700/70 first:rounded-t-md last:rounded-b-md">{{ item }}</li>
      </ul>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import OverlayInvisible from './OverlayInvisible.vue'

const props = defineProps({
  items: Array,
})

const isOpen = ref(false)
const toggleMenu = () => {
  isOpen.value = !isOpen.value
}
</script>
