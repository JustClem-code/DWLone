<template>
  <button type="button" @click="emit('click')"
    class="relative flex flex-col justify-center items-center w-full min-h-60 border-2 border-dashed border-gray-200 dark:border-gray-700/90 rounded-xl p-4 sm:p-8">
    <slot></slot>
    <span>{{ title }}</span>
  </button>
</template>

<script setup>

const props = defineProps({
  title: String,
})

const emit = defineEmits(['click'])

</script>
