<template>
  <div class="relative w-full border border-solid border-gray-200 dark:border-gray-700/90 rounded-xl p-8">
    <h2 class="absolute -top-4 text-xl font-light text-gray-300 dark:text-gray-700/90 tracking-wide bg-gray-100 dark:bg-gray-900 px-2">{{ title }}</h2>
    <slot></slot>
  </div>
</template>

<script setup>
defineProps({
  title: String
});
</script>
