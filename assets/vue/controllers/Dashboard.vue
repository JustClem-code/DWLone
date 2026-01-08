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

// finir la logic de stow côter vue et php
// créer un componant "horizontal link button"
// créer un componant "header packages stack"
//
// wip sur les locations
// https://tailwindcss.com/plus/ui-blocks/application-ui/lists/grid-lists

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
