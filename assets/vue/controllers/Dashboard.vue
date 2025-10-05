<template>
  <div>Hello {{ user_name }}!</div>
</template>

<script setup>

//TODO:
// animate-pulse pour les skeleton loader
// Terminer la nav bar côté mobile
// dark mode
// slot component pour les bordures en contour (je me comprends) ?
// terminer la modal de dialog, select item and valid button
// gérer la réponse de docking côté front
// truck name au lieu de truck wrid ?

import { onMounted } from 'vue'
defineProps({
  user_name: String
});

onMounted(() => {
  console.log(`the component is now mounted.`)
})

</script>
