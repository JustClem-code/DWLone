<template>
  <svg :class="[size ?? 'size-6', color ?? '']" viewBox="0 0 20 20" fill="currentColor">
    <title>{{ title }}</title>
    <path
      d="M10 3a1.5 1.5 0 110 3 1.5 1.5 0 010-3ZM10 8.5a1.5 1.5 0 110 3 1.5 1.5 0 010-3ZM11.5 15.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0Z" />
  </svg>
</template>

<script setup>

const props = defineProps({
  size: String,
  color : String,
  title: String
})

</script>
