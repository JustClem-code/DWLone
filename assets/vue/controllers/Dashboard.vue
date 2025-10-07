<template>
  <BorderedContent title="Welcome">
    <div>Hello {{ user_name }}!</div>
  </BorderedContent>
</template>

<script setup>

//TODO:
// ajouter des loader
// animate-pulse pour les skeleton loader
// dark mode -> finir la modal de dialog, gestion de variables global
// gestion des dates...
// slot component pour les bordures en contour (je me comprends) ?
// terminer la modal de dialog, select item and valid button
// gérer la réponse de docking côté front
// truck name au lieu de truck wrid ?

import { onMounted } from 'vue'
import BorderedContent from './UI/BorderedContent.vue';
defineProps({
  user_name: String
});

onMounted(() => {
  console.log(`the component is now mounted.`)
})

</script>
