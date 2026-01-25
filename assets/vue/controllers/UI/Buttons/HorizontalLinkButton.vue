<template>

  <button type="button" @click="emit('click')"
    class="flex justify-center items-center w-full bg-white/40 dark:bg-gray-800/50 border rounded-md shadow-xs dark:shadow-none border-gray-300 dark:border-gray-700/90 hover:border-gray-500 p-1 md:p-4 cursor-pointer"
    :class="{ 'animate-pulse': isLoading }, focused">
    <h3 class="text-xs md:text-base font-light md:font-semibold">{{ title }}</h3>
  </button >

</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  title: String,
  focused: String,
  isLoading: Boolean,
})

const emit = defineEmits(['click'])

</script>
