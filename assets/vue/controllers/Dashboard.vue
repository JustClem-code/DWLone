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

// attribuer un bag à location avant d'attribuer une location à package
// attibuer une location en fonction du postcode

// bien gérer l'animation du drag and drop et du cursor

// voir pour reset location sur package et reset la palette correspondante

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
