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

// mettre les function liée à packages dans un store pour éviter les duplications (getPackagesNotInducted) ?

// Refacto les methods des repository

// déplacer certaines logiques (métier, notification dans les composables ?)

// gestion du animate pulse si chargement de la list => voir tailwind UI
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
