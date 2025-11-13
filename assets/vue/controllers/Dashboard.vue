<template>
  <BorderedContent title="Welcome">
    <div>Hello {{ user_name ?? 'John Doe' }}!</div>
  </BorderedContent>
</template>

<script setup>

//TODO:
// Mettre en place utilisateurs
// Tester si c'est bien
// => composer require symfonycasts/verify-email-bundle
// => php bin/console make:registration-form

// gestion du animate pulse si chargement de la list => voir tailwind UI
// ça serait cool de passer à la reception avant induction


import { onMounted } from 'vue'
import BorderedContent from './UI/BorderedContent.vue';
const props = defineProps({
  is_user: Boolean,
  user_name: String
});

onMounted(() => {
  console.log(`the component is now mounted.`, props.is_user)
})

</script>
