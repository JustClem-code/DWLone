<template>
  <BorderedContent title="Welcome">
    <div>Hello {{ user_name }}!</div>
  </BorderedContent>
</template>

<script setup>

//TODO:
// créer un composant pour les svg (close cross) => IconButton et regrouper ?
// Trier le reste des composants UI
// gestion du animate pulse si chargement de la list => voir tailwind UI
// transformer les function en arrow function si possible
// ça serait cool de passer à la reception avant induction


import { onMounted } from 'vue'
import BorderedContent from './UI/BorderedContent.vue';
defineProps({
  user_name: String
});

onMounted(() => {
  console.log(`the component is now mounted.`)
})

</script>
