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

      <BorderedContent title="Setting" width="w-1/2">
        <ProcessSetting />
      </BorderedContent>

    </div>

    <BorderedContent title="Trucks">
      <TrucksProcessing />
    </BorderedContent>

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

// TailWind plus n'est plus accessible => https://headlessui.com

// dans le proccessSetting -> gérer les groupPostcodes, l'ouverture des allée et la gestion du snake path

// -> attribuer un groupPostCode à une allée qui peut être ouverte
// -> ajouter un champs boolean enabled à l'entité location

// dans le dashboard, touver un moyen d'étider les groupPostcodes

// Mettre à joir les infos des bags après le sequencing et le stagging
// Remplacer le code svg dans SearchComponent par un component

// Afficher le plan des stagging avec les emplacements occupés

//  -> revoir la manière d'afficher la pairLocation le "&" est moche


// Nettoyage de tous les console.log (oui oui)

// mettre un sous-menu avec #id sur les partis pour éviter de scroller ?
// -> https://headlessui.com/v1/react/tabs


// WIP : Revoir les method de repository to array pour limiter les données inutiles ou dupliquée

// gestion du animate pulse si chargement de la (list / HorizontalLinkButton et divers composants) => voir tailwind UI
// plus globalement, il faut gérer le chargement côté vue et twig

import { onMounted, provide } from 'vue'

import { userStore } from '../composables/userStore.js'

import BorderedContent from './UI/BorderedContent.vue';
import PackagesStats from './DashboardComponents/PackagesStats.vue'
import BagsProcessing from './DashboardComponents/BagsProcessing.vue';
import PickingProcessing from './DashboardComponents/PickingProcessing.vue';
import TrucksProcessing from './DashboardComponents/TrucksProcessing.vue';
import ProcessSetting from './DashboardComponents/ProcessSetting.vue';

const props = defineProps({
  is_user: Boolean,
  user_name: String
});

const { userName } = userStore()

// const { isRunning } = useTimer()

onMounted(() => {
  console.log(`the component is now mounted.`)
})

/* watch(
  locations,
  (val) => {
    console.log('locations', val)
  },
  { deep: true }
) */

</script>
