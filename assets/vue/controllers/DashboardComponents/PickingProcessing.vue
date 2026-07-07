<template>
  <div class="flex flex-col gap-8">

    <StatsHeader title="Picking processing" notice="You can see the picking overview" actionTitle="Action on picking"
      @actionClick="sidePanelRef?.toggleSidePanel()" :statistics="pickingStats" />

    <RoadPartsList v-if="allRoadParts" :roadParts="allRoadParts" />
    <div v-else-if="errorGetAllRoadParts">Error: {{ errorGetAllRoadParts }}</div>
    <div v-else>Loading...</div>

    <SidePanel ref="sidePanelRef" title="Action on picking" width="md:w-5/6">

      <!-- <div v-if="locations" class="divide-y divide-gray-200 dark:divide-gray-700/90">
        <div v-for="(groupe, indexGroup) in locations" :key="indexGroup" class="grid grid-cols-6 gap-1 sm:gap-4 py-8">

          <HorizontalLinkButton v-for="location in groupe" :key="location.id" @click="setCurrentBag(location.bag)"
            :title="location.name"
            :focused="location.bag?.packages?.length > 0 ? getBagColorZoom(location.bag?.name) : 'text-gray-200 dark:text-gray-700/90'" />

        </div>
      </div> -->

    </SidePanel>

    <!-- <DialogComponentSlot ref="infoDialogRef" :hasCloseCross="true">
      <InformationComponent :informations="bagInfos" />
    </DialogComponentSlot> -->

  </div>
</template>

<script setup>
import { ref, computed, provide, watch } from 'vue';
import { useLogic } from '../../composables/useLogic.js'
import { useFetch, usePostFetch } from '../../composables/fetch.js'

import SidePanel from '../UI/SidePanel.vue';
import HorizontalLinkButton from '../UI/Buttons/HorizontalLinkButton.vue';
import DialogComponentSlot from '../UI/Modals/DialogComponentSlot.vue';
import InformationComponent from '../UI/Modals/InformationComponent.vue';
import StatsHeader from './StatsHeader.vue';
import RoadPartsList from './RoadPartsList.vue';

const { formatInt, getColor } = useLogic()

const { data: allRoads, error: errorGetAllRoads } = useFetch('/getAllRoads')
const { data: allRoadParts, error: errorGetAllRoadParts } = useFetch('/getAllRoadParts')

const currentBag = ref(null)
const infoDialogRef = ref(null)

const sidePanelRef = ref(null)

const globalLoading = ref(null)

const setCurrentBag = (bag) => {
  if (!bag) {
    return
  }
  currentBag.value = bag;
  infoDialogRef.value?.openDialog()
}

const numberOfBags = computed(() =>
  locations.value
    ? locations.value
      .flatMap(group => group.filter(loc => loc.bag !== null))
      .length
    : 0
)

const pickingStats = computed(() => [
  { 'title': 'Number of roads', 'number': `${allRoadParts.value?.length}` },
  { 'title': 'Number of road', 'number': `0` },
  { 'title': 'Picking progress', 'number': `0` },
])

async function resetRoadpart(roadPart) {
  globalLoading.value = true;

  const { data, error } = await usePostFetch(`/resetRoadPart/${roadPart.id}`)

  if (data.value) {
    // currentPackage.value = data.value
    updateCurrentPallet()
    setTimeout(() => {
      globalLoading.value = false;
    }, 500);
    setTimeout(() => {
      notifier('success', 'Induction', `The package (Id: ${currentPackage.value.id}) is inducted`)
    }, 1000);
    setTimeout(() => {
      // currentPackage.value = null
    }, 1500);
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
