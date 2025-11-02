<template>
  <BorderedContent title="Welcome">
    <div>Hello {{ user_name }}!</div>
  </BorderedContent>
</template>

<script setup>

//TODO:
// possibilité de voir les infos du truc dans le minimal toggle => finir
// Ajouter une croix de fermeture
// gestion du animate pulse si chargement de la list
// ajout d'un système de notification
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
