<template>
  <svg :class="[size ?? 'size-6', color ?? '']" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
    stroke-width="1.5" stroke="currentColor">
    <title>{{ title }}</title>
    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
  </svg>
</template>

<script setup>

const props = defineProps({
  size: String,
  color: String,
  title: String
})

</script>
