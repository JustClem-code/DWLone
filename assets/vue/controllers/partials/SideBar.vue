<template>
  <aside class="fixed w-full h-screen top-0 bg-gray-100 dark:bg-gray-900 shadow-lg z-50" :class="open ? 'left-0' : '-left-full'">
    <div class="flex items-center justify-between flex-wrap border-b border-solid bg-white dark:bg-gray-800/50 border-gray-200 dark:border-gray-700/90 p-6">
      <LogoTitle />
      <div class="block md:hidden p-3">
        <CloseCrossButton @click="$emit('open', open)" />
      </div>
    </div>
    <div class="w-full flex-grow md:flex md:items-center md:w-auto block md:hidden p-6">
      <NavContent />
    </div>
  </aside>
</template>

<script setup>
import CloseCrossButton from '../UI/Buttons/CloseCrossButton.vue';
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
