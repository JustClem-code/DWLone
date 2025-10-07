<template>
  <aside class="fixed w-full h-screen top-0 bg-gray-100 dark:bg-gray-900 shadow-lg z-50" :class="open ? 'left-0' : '-left-full'">
    <div class="flex items-center justify-between flex-wrap border-b border-solid bg-white dark:bg-gray-800/50 border-gray-200 dark:border-gray-700/90 p-6">
      <LogoTitle />
      <div class="block md:hidden">
        <button class="flex items-center p-3 hover:text-gray-700" :class="open ? 'left-0' : '-left-full'"
          @click="$emit('open', open)">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
            stroke="currentColor" class="h-3 w-3">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
    </div>
    <div class="w-full flex-grow md:flex md:items-center md:w-auto block md:hidden p-6">
      <NavContent />
    </div>
  </aside>
</template>

<script setup>
import LogoTitle from './LogoTitle.vue';
import NavContent from './NavContent.vue';

const props = defineProps({
  open: Boolean,
})
const emit = defineEmits(['open'])

</script>

<style scoped>
aside {
  transition: left 0.3s ease-in-out;
}
</style>
