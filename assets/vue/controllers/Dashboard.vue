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
      <!-- <div class="grid grid-cols-4 gap-4">
        <div class="grid grid-cols-6 gap-1">
          <div v-for="location, i in orderedLocations" :key="location.id">
            <div v-if="i < 156" class="size-1 bg-gray-100"
              :class="location.bag?.packages?.length > 0 ? getBagColor(location.bag?.name) : ''">

            </div>
          </div>
        </div>
      </div> -->

      <div v-if="orderedLocations">
        <div v-for="(rang, indexRang) in orderedLocations" :key="indexRang">
          <div v-for="(emplacement, indexEmpl) in rang" :key="emplacement.id">
            {{ emplacement.name }} (ID: {{ emplacement.id }})
          </div>
        </div>
      </div>



     <!--  <div class="grid grid-cols-4 gap-4">
        <div v-for="(groupe, indexGroupe) in orderedLocations" :key="indexGroupe" class="grid grid-cols-6 gap-1">

          <div v-for="(location, indexElement) in groupe" :key="indexElement" class="size-1 bg-gray-100"
            :class="location.bag?.packages?.length > 0 ? getBagColor(location.bag?.name) : ''">

          </div>

        </div>
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

const getBagColor = (name) => {
  const prefix = name.match(/^[^-]+/)[0];
  const colors = {
    'BLK': 'outline-2 outline-offset-2',
    'NVY': 'outline-2 outline-blue-700 outline-offset-2',
    'ORG': 'outline-2 outline-orange-700 outline-offset-2',
    'YLO': 'outline-2 outline-yellow-700 outline-offset-2',
    'GRN': 'outline-2 outline-green-700 outline-offset-2',
  }
  return colors[prefix] ?? '';
}

const orderedLocations = computed(() => {
  const byKey = new Map(locations.value?.map(loc => [loc.name, loc]) || []);
  const result = [[], [], [], []];

  const aisleLetters = ['C', 'B'];
  const bagLetters = ['A', 'B', 'C', 'D', 'E', 'G'];
  const bagLettersReverse = [...bagLetters].reverse();
  const orderSpecsPair = [{ side: '1' }, { side: '2' }];
  const orderSpecsOdd = [{ side: '2' }, { side: '1' }];
  let indexGlobal = 0;
  let arrayIndex = 0;

  for (const aisleLet of aisleLetters) {
    for (let floor = 1; floor <= 52; floor++) {
      const letters = floor <= 26 ? bagLettersReverse : bagLetters;
      const specs = floor % 2 === 0 ? orderSpecsPair : orderSpecsOdd;

      for (const spec of specs) {
        for (const letter of letters) {
          const key = `${aisleLet}-${floor}-${letter}-${spec.side}`;
          const loc = byKey.get(key);

          if (loc) {
            const arrayIndex = Math.floor(indexGlobal / 312);
            if (arrayIndex < 4) {
              result[arrayIndex].push(loc);
            }
          }
          indexGlobal++;


          if (result[arrayIndex] && result[arrayIndex].length >= 312) {
            arrayIndex++;
          }
        }
      }
    }
  }


  /* result.forEach((subArray, index) => {
    while (subArray.length < 312) {
      result[index].push(null);
    }
  }); */

  return result;
});


/* const orderedLocations = computed(() => {
  const byKey = new Map(locations.value?.map(loc => [loc.name, loc]) || []);
  const result = [];

  const aisleLetters = ['C', 'B'];
  const bagLetters = ['A', 'B', 'C', 'D', 'E', 'G'];
  const bagLettersReverse = [...bagLetters].reverse();
  const orderSpecsPair = [{ side: '1' }, { side: '2' }];
  const orderSpecsOdd = [{ side: '2' }, { side: '1' }];

  for (const aisleLet of aisleLetters) {
    for (let floor = 1; floor <= 52; floor++) {
      const letters = floor <= 26 ? bagLettersReverse : bagLetters;
      const specs = floor % 2 === 0 ? orderSpecsPair : orderSpecsOdd;

      for (const spec of specs) {
        for (const letter of letters) {
          const key = `${aisleLet}-${floor}-${letter}-${spec.side}`;
          const loc = byKey.get(key);
          if (loc) result.push(loc);
        }
      }
    }
  }

  return result;
}); */


provide('dashboard', { locations, orderedLocations })

/* watch(
  orderedLocations,
  (val) => {
    console.log('ordered location', val)
  },
  { deep: true }
) */

</script>
