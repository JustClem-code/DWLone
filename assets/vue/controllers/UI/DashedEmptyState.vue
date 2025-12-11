<template>
  <button type="button" @click="emit('click')"
    class="relative flex flex-col justify-center items-center w-full min-h-60 border-2 border-dashed border-gray-200 dark:border-gray-700/90 rounded-xl p-4 sm:p-8"
    :disabled="disabled">
    <slot></slot>
    <span>{{ title }}</span>
  </button>
</template>

<script setup>

const props = defineProps({
  title: String,
  disabled: Boolean,
})

const emit = defineEmits(['click'])

</script>
