<template>
  <BorderedContent title="Welcome">
    <div>Hello {{ user_name }}!</div>
  </BorderedContent>
</template>

<script setup>

//TODO:
// gèrer updateListElements au niveau des retours de data null
// Ne pas proposer un dock si occupé
// gestion du bouton select en contexte list (pfiou)
// gestion du animate pulse si chargement de la list
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
