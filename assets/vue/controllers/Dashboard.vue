<template>
  <div class="flex flex-col gap-8">
    <div class="flex gap-8">
      <BorderedContent title="Welcome" width="w-1/2">
        <div>
          <h2 class="text-2xl">
            Hello {{ userName ?? 'John Doe' }}!
          </h2>
        </div>
      </BorderedContent>
      <BorderedContent title="Exemple" width="w-1/2">
        <h2>Exemple</h2>
      </BorderedContent>
    </div>
    <BorderedContent title="Statistics">
      <PackagesStats />
    </BorderedContent>
    <BorderedContent title="Bags">
      <BagsProcessing />
    </BorderedContent>

  </div>
</template>

<script setup>

//TODO:

// STAT UI : https://tailwindcss.com/plus/ui-blocks/application-ui/data-display/stats
// PROGRESS BAR : https://tailwindcss.com/plus/ui-blocks/application-ui/navigation/progress-bars
// DRAWERS : https://tailwindcss.com/plus/ui-blocks/application-ui/overlays/drawers

// travailler l'UI de la page dashboard

// Pouvoir faire une recherche sur les bags et trouver une routes ou l'emplacement

// Filtrer en fonctions des locations vides
// calculer le nombre de bag plein avec des packages
// afficher les stats des pallet attentu, packages inducté et en stow, ect...

// WIP : Revoir les method de repository to array pour limiter les données inutiles ou dupliquée

// gestion du animate pulse si chargement de la (list / HorizontalLinkButton et divers composants) => voir tailwind UI
// plus globalement, il faut gérer le chargement côté vue et twig

import { onMounted, watch, ref, provide, computed } from 'vue'

import { userStore } from '../composables/userStore.js'
import { useFetch } from '../composables/fetch.js'

import BorderedContent from './UI/BorderedContent.vue';
import PackagesStats from './DashboardComponents/PackagesStats.vue'
import BagsProcessing from './DashboardComponents/BagsProcessing.vue';

const props = defineProps({
  is_user: Boolean,
  user_name: String
});

const { userName } = userStore()

const { data: locations, error: errorLocations } = useFetch('/getBagsInLocations')

onMounted(() => {
  console.log(`the component is now mounted.`)
})

provide('dashboard', { locations })

/* watch(
  locations,
  (val) => {
    console.log('locations', val)
  },
  { deep: true }
) */

</script>
