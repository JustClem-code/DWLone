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

// travailler l'UI de la page dashboard

// STAT UI : https://tailwindcss.com/plus/ui-blocks/application-ui/data-display/stats
// PROGRESS BAR : https://tailwindcss.com/plus/ui-blocks/application-ui/navigation/progress-bars
// DRAWERS : https://tailwindcss.com/plus/ui-blocks/application-ui/overlays/drawers

// Créer un fichier store pour redéfinir les locations ou le faire directement dans le php ?
// WIP creation de private function, vérifier la structure et qu'il n'y a pas trop de array imbriqués pour une bonne itération dans vue
//

// afficher les stats des pallet attentu, truckc, ect...

// refacto les methods de getBagColor
// retrouver les stats des bags
// Revoir l'ordre des locations sur la stow
// Pouvoir faire une recherche sur les bags et trouver une routes ou l'emplacement


// Créer le controller Picking
//  -> générer les routes (combien de sacs par chariot, 1 chariot par routes ?)
//  -> tables emplacement chariot picking ?
//  -> lier les codes postaux en fonction de la localisation
//  -> afficher "heavy bag" si dépasse un certain poids
//  -> trouver un moyen pour se déplacer dans l'entrepôt -> reprendre celui de la stow plus ajouter les emplacements de staging
//  -> Staging en Sidepanel
//  -> revoir la manière d'afficher la pairLocation le "&" est moche




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
