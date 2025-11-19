<template>
  <div class="relative inline-block text-left">

    <button @click="toggleMenu" class="flex items-center justify-center active:inset-ring-2 inset-ring-gray-300 dark:inset-ring-gray-600/50 rounded-full size-9">
      <AvatarCircle size="size-9"/>
    </button>

    <OverlayInvisible v-show="isOpen" @click="toggleMenu" />
    <div v-show="isOpen"
      class="absolute right-0 mt-2 w-44 origin-top-right z-10 rounded-md shadow-lg bg-white dark:bg-gray-800 border border-0 dark:border-1 dark:border-gray-700/90"
      role="menu">
      <header class="flex items-center gap-3 p-3 border-b border-gray-200 dark:border-gray-700/90">
        <AvatarCircle size="size-10"/>
        <p class="capitalize">{{ userName }}</p>
      </header>
      <ul class="pt-3">
        <a type="button" v-for="item in items" :key="item.name" :aria-disabled="item.isDisabled" @click="item.click"
          class="block px-4 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-700/70 last:rounded-b-md cursor-pointer">
          <div class="flex items-center gap-4">
            <component :is="item.icon" :size="item.sizeIcon" />
            <span>
              {{ item.name }}
            </span>
          </div>
        </a>
      </ul>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import OverlayInvisible from '../UI/OverlayInvisible.vue'
import { userStore } from '../../composables/userStore.js'
import AvatarCircle from './AvatarCircle.vue'

const { userName } = userStore()

const props = defineProps({
  items: Array,})

const isOpen = ref(false)
const toggleMenu = () => (
  isOpen.value = !isOpen.value
)

</script>
