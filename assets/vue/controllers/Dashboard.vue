<template>
  <BorderedContent title="Welcome">
    <div>
      <h2 class="text-2xl">
        Hello {{ userName ?? 'John Doe' }}!
      </h2>
    </div>
  </BorderedContent>
</template>

<script setup lang="ts">

//TODO:

// éviter les reset trucks si pallet on floor et pallet si packages inducté

// Refacto les methods des repository

// gestion du animate pulse si chargement de la list => voir tailwind UI
// plus globalement, il faut gérer le chargement côté vue et twig


import { onMounted } from 'vue'
import BorderedContent from './UI/BorderedContent.vue';

import { userStore } from '../composables/userStore.js'

const props = defineProps < {
  is_user: Boolean,
  user_name: String
} > ()


const { userName } = userStore()

onMounted(() => {
  console.log(`the component is now mounted.`)
})

import { ref, computed } from 'vue'

const count = ref<number>()

// type déduit : ComputedRef<number>
// const double = computed(() => count.value * 2)

// => TS Error: Property 'split' does not exist on type 'number'

const double = computed<number>(() => {
  return count.value * 2
  // erreur de type si un nombre n'est pas retourné
})

</script>
