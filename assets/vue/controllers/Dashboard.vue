<template>
  <BorderedContent title="Welcome">
    <div>Hello {{ userName ?? 'John Doe' }}!</div>
    <button @click="logOut">Log out</button>
  </BorderedContent>
</template>

<script setup>

//TODO:
// Mettre en place utilisateurs, mieux gérer le login côter vue.js (si possible)
// s'inpirer de github pour l'avatar et les boutons de login, register

// Ajouter des relations user à trucks, pallets, stow etc

// déplacer certaines logiques (métier, notification dans les composables ?)
// gestion du animate pulse si chargement de la list => voir tailwind UI
// ça serait cool de passer à la reception avant induction


import { onMounted } from 'vue'
import BorderedContent from './UI/BorderedContent.vue';

import { userStore } from '../composables/userStore.js'

const props = defineProps({
  is_user: Boolean,
  user_name: String
});

const { userName, logOut } = userStore()

onMounted(() => {
  console.log(`the component is now mounted.`)
  console.log('getUser in dashboard', userName);

})

</script>
