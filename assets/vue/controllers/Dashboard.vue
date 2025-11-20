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
// Ajouter des relations user à trucks, pallets, stow etc

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
