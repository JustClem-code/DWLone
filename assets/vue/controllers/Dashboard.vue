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

    <BorderedContent title="Picking">
      <PickingProcessing />
    </BorderedContent>

  </div>
</template>

<script setup>

//TODO:

// STAT UI : https://tailwindcss.com/plus/ui-blocks/application-ui/data-display/stats
// PROGRESS BAR : https://tailwindcss.com/plus/ui-blocks/application-ui/navigation/progress-bars
// DRAWERS : https://tailwindcss.com/plus/ui-blocks/application-ui/overlays/drawers

// proposer de reprendre une route à la fin de la route s'il reste des routes

// RESET la route côté Dashboard

// mettre un soumenu avec #id sur les partis pour éviter de scroller ?

// Ajouter l'état du picking par bag dans BagPprocessing (Dashboard)

// retrouver les bag dans les allées avant de tester un snake path

// creation d'un snake path côté Symfony pour trier les bag dans roadPart en fonction du snake path

// Reefacto SetUserToStow pour faire la vérification de la bonne location côté symfony


// Afficher le plan des stagging avec les emplacements occupés

//  -> revoir l'attribution des emplacements des sacs et touver le groupe des postcodes de livraison
//      pour les mettres dans la même aislepair


//  -> lier les codes postaux en fonction de la localisation

//  -> revoir la manière d'afficher la pairLocation le "&" est moche

// Empêcher le Picking si le stow n'est pas fini

 /* GÉRER CE PROBLÈME

 [WARNING] You have 6 previously executed migrations in the database that are
           not registered migrations.


 >> 2026-03-20 15:59:45 (DoctrineMigrations\Version20260320155942)
 >> 2026-03-20 16:02:58 (DoctrineMigrations\Version20260320160251)
 >> 2026-03-20 16:04:22 (DoctrineMigrations\Version20260320160419)
 >> 2026-03-20 16:08:40 (DoctrineMigrations\Version20260320160836)
 >> 2026-03-20 16:09:47 (DoctrineMigrations\Version20260320160945)
 >> 2026-03-20 16:11:36 (DoctrineMigrations\Version20260320161133)

 */

// afficher les stats des pallet attentu, truckc, ect...

// Pouvoir faire une recherche sur les bags et trouver une routes ou l'emplacement

// Refacto certaines fonctions trop longue -> YardTruckController

// WIP : Revoir les method de repository to array pour limiter les données inutiles ou dupliquée

// gestion du animate pulse si chargement de la (list / HorizontalLinkButton et divers composants) => voir tailwind UI
// plus globalement, il faut gérer le chargement côté vue et twig

import { onMounted, provide } from 'vue'

import { userStore } from '../composables/userStore.js'
import { useFetch } from '../composables/fetch.js'
import { useTimer } from '../composables/useTimer.js'

import BorderedContent from './UI/BorderedContent.vue';
import PackagesStats from './DashboardComponents/PackagesStats.vue'
import BagsProcessing from './DashboardComponents/BagsProcessing.vue';
import PickingProcessing from './DashboardComponents/PickingProcessing.vue';

const props = defineProps({
  is_user: Boolean,
  user_name: String
});

const { userName } = userStore()

const { data: locations, error: errorLocations } = useFetch('/getBagsInLocations')

const { isRunning } = useTimer()

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
