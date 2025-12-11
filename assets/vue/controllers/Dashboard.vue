<template>
  <BorderedContent title="Welcome">
    <div>
      <h2 class="text-2xl">
        Hello {{ userName ?? 'John Doe' }}!
      </h2>
    </div>
  </BorderedContent>
</template>

<script setup>

//TODO:

// Finir la request setLocation

// supprimer current pallet et current package si pallet vide
// -> mettre un autre composants pour demander de supprimer currentPallet ou de changer de pallet ?
// bouger le bouton reset location and bag et mettre une confirmation
// empêcher le Reset UNLoading si un package a été inducté


// mettre les function liée à packages dans un store pour éviter les duplications (getPackagesNotInducted) ?

// bien gérer l'animation du drag and drop et du cursor
// mettre en surbrillance le package à drag

// Refacto les methods des repository

// Gérer le account circle en mobile
// Vérifier contours notification vers light theme

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

const { userName } = userStore()

onMounted(() => {
  console.log(`the component is now mounted.`)
})

</script>
