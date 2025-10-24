<template>
  <BorderedContent title="Welcome">
    <div>Hello {{ user_name }}!</div>
  </BorderedContent>
</template>

<script setup>

//TODO:
// Bouger le bouton unDocking
// gestion du animate pulse si chargement de la list
// Modifier le selectDialogComponent pour y mettre un slot
// gestion des dates...
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
