<template>
  <svg :class="[size ?? 'size-6', color ?? '']" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
    <title>{{ title }}</title>
    <path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z" />
  </svg>
</template>

<script setup>

const props = defineProps({
  size: String,
  color: String,
  title: String
})

</script>
