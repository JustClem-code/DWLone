<template>
  <svg :class="[size ?? 'size-6', color ?? '']" viewBox="0 0 20 20" fill="currentColor" data-slot="icon"
    aria-hidden="true">
    <title>{{ title }}</title>
    <path
      d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z"
      clip-rule="evenodd" fill-rule="evenodd"></path>
  </svg>
</template>

<script setup>

const props = defineProps({
  size: String,
  color: String,
  title: String
})

</script>
