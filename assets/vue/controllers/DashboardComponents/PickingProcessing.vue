<template>
  <div class="flex flex-col gap-8">

    <StatsHeader title="Picking processing" notice="You can see the picking overview" actionTitle="Action on picking"
      @actionClick="sidePanelRef?.toggleSidePanel()" :statistics="pickingStats" />

    <RoadPartsList v-if="allRoadParts" :roadParts="allRoadParts" />
    <div v-else-if="errorGetAllRoadParts">Error: {{ errorGetAllRoadParts }}</div>
    <div v-else>Loading...</div>

    <SidePanel ref="sidePanelRef" title="Action on picking" width="md:w-5/6">
      <div class="flex flex-col gap-2 mb-8">

        <RadioCard v-for="option in automaticOptions" :key="option.value" :option="option" v-model="selected" />

        <BaseButton class="mt-4" @click="submitAutomaticForm" title="Automatic program" styleColor="primary"
          :isDisabled="!selected" :isLoading="globalLoading" />
      </div>


    </SidePanel>

  </div>
</template>

<script setup>
import { ref, computed, provide, watch } from 'vue';

import { useLogic } from '../../composables/useLogic.js'
import { useFetch, usePostFetch } from '../../composables/fetch.js'
import { useNotification } from '../../composables/eventBus.js'

import SidePanel from '../UI/SidePanel.vue';
import HorizontalLinkButton from '../UI/Buttons/HorizontalLinkButton.vue';
import DialogComponentSlot from '../UI/Modals/DialogComponentSlot.vue';
import InformationComponent from '../UI/Modals/InformationComponent.vue';
import StatsHeader from './StatsHeader.vue';
import RoadPartsList from './RoadPartsList.vue';
import RadioCard from '../UI/Radios/RadioCard.vue';
import BaseButton from '../UI/Buttons/BaseButton.vue';

const { formatInt, getColor } = useLogic()

const { notifier } = useNotification()

const { data: allRoads, error: errorGetAllRoads } = useFetch('/getAllRoads')
const { data: allRoadParts, error: errorGetAllRoadParts } = useFetch('/getAllRoadParts')

const selected = ref(null)

const sidePanelRef = ref(null)

const globalLoading = ref(null)

const allRoadPartsNumber = computed(() => {
  return allRoadParts.value ? allRoadParts.value.length : 0
})

const allRoadartsWithUser = computed(() => {
  return allRoadParts.value ? allRoadParts.value.filter( r => r.userName ).length : 0
})

const allRoadartsStagged = computed(() => {
  return allRoadParts.value ? allRoadParts.value.filter( r => r.stagged ).length : 0
})

const pickingStats = computed(() => [
  { 'title': 'Number of roads', 'number': `${allRoadPartsNumber.value}` },
  { 'title': 'Number of road', 'number': `${allRoadartsWithUser.value}` },
  { 'title': 'Picking progress', 'number': `0` },
])


const automaticOptions = computed(() => [
  { 'value': 'Sequencing', 'notice': 'Generate roads', 'number': `not defined`, 'disabled': allRoadPartsNumber.value > 0 },
  { 'value': 'Delete', 'notice': 'Delete all road parts', 'number': `${allRoadPartsNumber.value}`, 'disabled': allRoadPartsNumber.value === 0 },
  { 'value': 'Hard reset', 'notice': 'Reset all road parts', 'number': `${allRoadartsWithUser.value}`, 'disabled': allRoadartsWithUser.value === 0 },
])

function submitAutomaticForm() {
  /* const actions = {
    'Induct': () => automaticInduct(true, false),
    'Stow': () => automaticInduct(false, true),
    'Hard reset': () => resetLocationsBagsPackages(),
  }

  const run = actions[selected.value]

  if (!run) {
    console.log('error')
  } else {
    run()
  }

  selected.value = null */
}





const updateRoadParts = (roadPart) => {
  const index = allRoadParts.value.findIndex(item => item.id === roadPart.id);

  if (index !== -1) {
    allRoadParts.value.splice(index, 1, roadPart);
  }

}

async function resetRoadpart(roadPart) {
  globalLoading.value = true;

  const { data, error } = await usePostFetch(`/resetRoadPart/${roadPart.id}`)

  if (error.value) {
    setTimeout(() => {
      notifier('error', 'Error reset picking  ', `${error.value}`)
      globalLoading.value = false;
    }, 1000);
  }

  if (data.value) {
    setTimeout(() => {
      globalLoading.value = false;
    }, 500);
    setTimeout(() => {
      updateRoadParts(data.value)
      notifier('Picking', 'Induction', `The roadpart (Id: ${roadPart.id}) is reseted`)
    }, 1000);
  }
}

provide('pickingProcessing', { resetRoadpart, globalLoading })

watch(
  () => allRoadParts,
  (val) => {
    console.log('allRoadParts watch:', val);
  },
  { immediate: true, deep: true }
);



</script>
