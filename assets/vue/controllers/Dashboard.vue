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

// WIP : Revoir les method de repository to array pour limiter les données inutiles ou dupliquée
// créer un vignette Bag sur le dashboard
// afficher les bags dans la vignette

// revoir la gestion de la mise en Bag, regrouper les Bags des routes dans la même allée si possible donc des routes
// grouper en fonctions des postcode

// gestion du animate pulse si chargement de la (list / HorizontalLinkButton et divers composants) => voir tailwind UI
// plus globalement, il faut gérer le chargement côté vue et twig


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
