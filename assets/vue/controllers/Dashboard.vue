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

// Créer le controller Picking
//  -> revoir l'attribution des emplacements des sacs et touver le groupe des postcodes de livraison
//      pour les mettres dans la même aislepair
//  -> générer les routes (combien de sacs par chariot, 1 chariot par routes ?)
//  -> + ou - 50 sacs => 36 emplacements => 62 postcodes

//  -> grouper les Postcode par 6 ou 7 dans le dashbord avant de générer les routes ???
//  -> tables emplacement chariot picking ? => créer les CART 1 cart par emplacement 36 et attribuer aléatoirement
//  -> lier les codes postaux en fonction de la localisation
//  -> afficher "heavy bag" si dépasse un certain poids
//  -> trouver un moyen pour se déplacer dans l'entrepôt -> reprendre celui de la stow plus ajouter les emplacements de staging
//  -> Staging en Sidepanel
//  -> revoir la manière d'afficher la pairLocation le "&" est moche


// afficher les stats des pallet attentu, truckc, ect...

// Pouvoir faire une recherche sur les bags et trouver une routes ou l'emplacement


// WIP : Revoir les method de repository to array pour limiter les données inutiles ou dupliquée

// gestion du animate pulse si chargement de la (list / HorizontalLinkButton et divers composants) => voir tailwind UI
// plus globalement, il faut gérer le chargement côté vue et twig

import { onMounted, provide } from 'vue'

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
