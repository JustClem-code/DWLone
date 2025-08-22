<template>
  <h2 class="text-3xl font-bold underline"> Home controller in Vue</h2>
    <div>Hello {{ user_name }}!</div>
</template>

<script setup>
    defineProps({
        user_name: String
    });
</script>
