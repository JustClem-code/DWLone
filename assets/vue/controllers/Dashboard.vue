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
        <ProccessSetting />
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

// creation d'un snake path côté Symfony pour trier les bag dans roadPart en fonction du snake path

//  -> lier les codes postaux en fonction de la localisation
// dans le dashboard, touver un moyen d'étider les groupPostcodes


//  -> revoir l'attribution des emplacements des sacs et touver le groupe des postcodes de livraison
//      pour les mettres dans la même aislepair


// Afficher le plan des stagging avec les emplacements occupés

//  -> revoir la manière d'afficher la pairLocation le "&" est moche


// Nettoyage de tous les console.log (oui oui)

// mettre un sous-menu avec #id sur les partis pour éviter de scroller ?
// -> https://headlessui.com/v1/react/tabs


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
import ProccessSetting from './DashboardComponents/ProccessSetting.vue';

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
