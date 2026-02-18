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

      <!-- <div class="grid grid-cols-6 gap-4">
        <HorizontalLinkButton v-for="location in locations" :key="location.id" @click="setCurrentBag(location.bag)"
          :title="location.name" :focused="location.bag?.packages?.length > 0 ? getBagColor(location.bag?.name) : ''" />
      </div> -->

    </BorderedContent>

    <!-- <DialogComponentSlot ref="infoDialogRef" :hasCloseCross="true">
      <InformationComponent :informations="bagInfos" />
    </DialogComponentSlot> -->
  </div>
</template>

<script setup>

//TODO:

// régler les problèmes entities du profiler

// vérifier les convention de naming (styleColor => StyleColor ?)

// Finir de bien trier les allées d'abord C puis B (et oui)
// Create bags components in dashboard

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

const orderedLocations = computed(() => {

  const aisleLetters = ['B', 'C']

  const orderSpecsPair = [
    { side: '1' }, // col 3
    { side: '2' }, // col 4
  ]

  const orderSpecsOdd = [
    { side: '2' }, // col 1
    { side: '1' }, // col 2
  ]


  const bagLetters = ['A', 'B', 'C', 'D', 'E', 'G']
  const bagLettersReverse = [...bagLetters].reverse();


  const byKey = new Map(
    locations.value?.map(loc => [loc.name, loc])
  )

  const result = []

  for (const aisleLet of aisleLetters) {

    for (let index = 0; index < 52; index++) {

      let floor = index + 1
      let letters = []

      if (index < 26) {
        letters = bagLettersReverse
      } else {
        letters = bagLetters
      }

      if (floor % 2 === 0) {
        for (const spec of orderSpecsPair) {
          for (const letter of letters) {
            const key = `${aisleLet}-${floor}-${letter}-${spec.side}`

            const loc = byKey.get(key)
            if (loc) result.push(loc)
          }
        }
      } else {
        for (const spec of orderSpecsOdd) {
          for (const letter of letters) {
            const key = `${aisleLet}-${floor}-${letter}-${spec.side}`
            const loc = byKey.get(key)
            if (loc) result.push(loc)
          }
        }
      }

    }

  }

  return result
})

provide('dashboard', { locations, orderedLocations })

/* watch(
  orderedLocations,
  (val) => {
    console.log('ordered location', val)
  },
  { deep: true }
) */

</script>
