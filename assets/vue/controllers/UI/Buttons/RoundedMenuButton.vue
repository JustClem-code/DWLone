<template>
  <button type="button" id="menu-button-1"
    class="inline-flex w-8 h-8 items-center justify-center rounded-full text-gray-400 hover:text-gray-900 dark:hover:text-gray-100"
    aria-haspopup="true" :aria-expanded="isExpanded" @click="emit('click')">
    <svg class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
      <path
        d="M10 3a1.5 1.5 0 110 3 1.5 1.5 0 010-3ZM10 8.5a1.5 1.5 0 110 3 1.5 1.5 0 010-3ZM11.5 15.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0Z" />
    </svg>
  </button>
</template>

<script setup>

const props = defineProps({
  isExpanded: String
})

const emit = defineEmits(['click'])

</script>
