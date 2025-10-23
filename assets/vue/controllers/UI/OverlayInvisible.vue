<template>
  <div class="fixed top-0 right-0 h-screen w-screen" @click="emit('click')"></div>
</template>

<script setup>
const emit = defineEmits(['click'])
</script>
