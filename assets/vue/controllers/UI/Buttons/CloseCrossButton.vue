<template>
  <button class="flex items-center text-gray-300 hover:text-gray-900 dark:text-gray-700 dark:hover:text-gray-100"
    @click="emit('click')">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
      class="size-6">
      <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
    </svg>
  </button>
</template>

<script setup>

const emit = defineEmits(['click'])

</script>
