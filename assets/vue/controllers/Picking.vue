<template>
  <div>
    <BaseButton class="mt-4" @click="setUserToRoadPart" title="Get a road Part" styleColor="primary"
      :isDisabled="setUserToRoadPartIsLoading" :isLoading="setUserToRoadPartIsLoading" />
    <BorderedContent title="Floor">
      <!-- si Cart attribuer à USER -->
      <FloorStaggingArea :staggingAreas="staggingAreas" class="pb-8" />
      <FloorAisles :locations="locations" />
      <!-- Else Component de choix de chariot avec avec stagging  -->
    </BorderedContent>

    <SidePanel ref="sidePanelRef" :title="currentPair ? currentPair?.id : 'title'" width="md:w-5/6">

      <div v-if="currentPair" class="flex flex-col gap-8">
        <BorderedContent title="Cart">
          <!-- <PackageDrop :pairPackages="currentPairPackages" /> -->

        </BorderedContent>

        <BorderedContent title="Locations">
          <PairLocations :orderedLocations="currentPair.locations" />
        </BorderedContent>
      </div>

    </SidePanel>

  </div>
</template>

<script setup>

import { ref, provide, computed, watchEffect } from 'vue'

import { useFetch, usePostFetch } from '../composables/fetch.js'
import { useNotification } from '../composables/eventBus.js'

import BorderedContent from './UI/BorderedContent.vue'
import SidePanel from './UI/SidePanel.vue'

// import PackageDrop from './StowComponents/PackageDrop.vue'
import FloorAisles from './PickingComponents/FloorAisles.vue'
import PairLocations from './PickingComponents/PairLocations.vue'
import FloorStaggingArea from './PickingComponents/FloorStaggingArea.vue'
import BaseButton from './UI/Buttons/BaseButton.vue'

const { data: locations, error: errorLocations } = useFetch('/getLocationsLight')
const { data: staggingAreas, error: errorStaggingAreas } = useFetch('/getStaggingAreas')

const { notifier } = useNotification()

const stowingIsLoading = ref(false)
const setUserToRoadPartIsLoading = ref(false)

const currentPair = ref(null)
const currentRoadPart = ref(null)

const sidePanelRef = ref(null)

const currentPairPackages = computed(() => {
  const data = {
    "id": currentPair.value.id,
    "packages": currentPair.value.locations.flatMap(row =>
      row.packages.filter(p => p.userStow === null)
    )
  }
  return data
})

const setCurrentPair = (pair) => {
  currentPair.value = pair
  sidePanelRef.value?.toggleSidePanel()
}

async function setUserToRoadPart() {

  setUserToRoadPartIsLoading.value = true;

  const { data, error } = await usePostFetch(`/setRoadToUser`)

  if (error.value) {
    setTimeout(() => {
      console.log(error.value ?? 'Erreur inconnue');
      notifier('error', 'RoadPart', ` ${error.value ?? 'Erreur inconnue'}`)
    }, 1000);
    setTimeout(() => {
      setUserToRoadPartIsLoading.value = false;
    }, 1500);
  }

  if (data.value) {
    currentRoadPart.value = data.value
    // updateCurrentPairPackages()
    setTimeout(() => {
      notifier('success', 'RoadPart', `The package (Id: ${currentRoadPart.value.id}) is ready to pick`)
    }, 1000);
    setTimeout(() => {
      setUserToRoadPartIsLoading.value = false;
    }, 1500);
  }
}

// const setCurrentPackage = (pack) => currentPackage.value = pack

/* const updateCurrentPairPackages = () => {
  const pkgId = currentPackage.value?.id
  if (!pkgId) return

  currentPair.value.locations = currentPair.value.locations.map(row => ({
    ...row,
    packages: row.packages.filter(p => p.id !== pkgId)
  }))

  if (!locations.value) return

  locations.value = locations.value.map(aisle =>
    aisle.map(group => ({
      ...group,
      locations: group.locations.map(row => ({
        ...row,
        packages: (row.packages || []).filter(p => p.id !== pkgId),
      })),
    }))
  );
} */

/* async function stowPackage(loc) {

  stowingIsLoading.value = true;

  if (!currentPackage.value) {
    stowingIsLoading.value = false;
    return
  }

  if (loc.name !== currentPackage.value.location.name) {
    currentPackage.value = false;
    stowingIsLoading.value = false;
    notifier('error', 'Stow', 'Wrong bag')
    return
  }

  const { data, error } = await usePostFetch(`/setUserStow/${currentPackage.value.id}`)

  if (data.value) {
    currentPackage.value = data.value
    updateCurrentPairPackages()
    setTimeout(() => {
      notifier('success', 'Stow', `The package (Id: ${currentPackage.value.id}) is stowed`)
    }, 1000);
    setTimeout(() => {
      stowingIsLoading.value = false;
      currentPackage.value = null
    }, 1500);
  }
} */

provide('picking', { setCurrentPair, currentPair, stowingIsLoading })
// provide('stow', { setCurrentPair, currentPair, setCurrentPackage, currentPackage, stowPackage, stowingIsLoading })

/* const handleToggle = () => {

  if (!sidePanelRef.value?.isOpen) {
    currentPackage.value = null
  }
}

watchEffect(handleToggle) */

</script>
