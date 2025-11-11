<template>
  <svg :class="[size ?? 'size-6', color ?? '']" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" data-slot="icon" aria-hidden="true">
    <title>{{ title }}</title>
    <path d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" stroke-linecap="round"
      stroke-linejoin="round"></path>
  </svg>
</template>

<script setup>

const props = defineProps({
  size: String,
  color: String,
  title: String
})

</script>
