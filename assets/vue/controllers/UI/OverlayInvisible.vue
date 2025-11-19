<template>
  <div class="fixed top-0 right-0 h-screen w-screen z-10" @click="emit('click')"></div>
</template>

<script setup>
const emit = defineEmits(['click'])
</script>
