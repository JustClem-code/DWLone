<template>
  <div class="flex flex-col gap-8">
    <BorderedContent title="Docks">
      <div v-if="docks" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
        <InboundDockCard v-for="dock in docks" :key=dock.id :dock="dock" />
      </div>
      <div v-else-if="errorDock">Error: {{ errorDock }}</div>
      <div v-else class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
        <InboundDockCard v-for="i in 12" />
      </div>
    </BorderedContent>
    <BorderedContent title="Pallets">
      <PalletList v-if="palletsOnFloor" :pallets="palletsOnFloor" />
      <div v-else-if="errorPallet">Error: {{ errorPallet }}</div>
      <div v-else>Loading...</div>
    </BorderedContent>
  </div>
</template>

<script setup>

import { ref, provide, computed } from 'vue'

import BorderedContent from './UI/BorderedContent.vue'
import InboundDockCard from './UnloadingComponents.vue/InboundDockCard.vue'
import PalletList from './UnloadingComponents.vue/PalletList.vue'

import { useFetch, usePostFetch } from '../composables/fetch.js'
import { useNotification } from '../composables/eventBus.js'

const { notifier } = useNotification()

const { data: docks, error: errorDock } = useFetch('/getoccupieddocks')

const { data: palletsOnFloor, error: errorPallet } = useFetch('/getpalletsonfloor')

const unLoadingData = ref(null)
const unLoadingIsLoading = ref(false)

const updateListElements = () => {

  const palletInTruck = docks.value
    .flatMap(dock => dock.pallets || [])
    .find(pallet => pallet.id === unLoadingData.value.id)

  if (!palletInTruck) return

  Object.assign(palletInTruck, unLoadingData.value)

  const palletOnFloorIndex = palletsOnFloor.value.findIndex(p => p.id === palletInTruck.id)

  if (palletOnFloorIndex === -1) {
    palletsOnFloor.value.push(palletInTruck)
  } else {
    palletsOnFloor.value.splice(palletOnFloorIndex, 1)
  }

}

async function resetUnloadingPallet(pallet) {
  unLoadingIsLoading.value = true;

  const { data, error } = await usePostFetch(`/resetUnloadingPallet/${pallet.id}`)

  if (error.value) {
    setTimeout(() => {
      notifier('error', 'Error reset unloading', `${error.value}`)
    }, 1000);
    setTimeout(() => {
      unLoadingIsLoading.value = false;
      unLoadingData.value = null;
      return
    }, 1500);
  }

  if (data.value) {
    unLoadingData.value = data.value;
    updateListElements()
    unLoadingIsLoading.value = false;
    notifier('success', 'Reset', `The pallet (Id: ${unLoadingData.value.id}) is reset`)
  }
}

async function unloadingPallet(pallet) {
  unLoadingIsLoading.value = true;

  const { data, error } = await usePostFetch(`/unloadingPallet/${pallet.id}`)

  if (error.value) {
    setTimeout(() => {
      notifier('error', 'Error unloading', `${error.value}`)
    }, 1000);
    setTimeout(() => {
      unLoadingIsLoading.value = false;
      unLoadingData.value = null;
      return
    }, 1500);
  }

  if (data.value) {
    unLoadingData.value = data.value;
    updateListElements()
    unLoadingIsLoading.value = false;
    notifier('success', 'Unloading', `The pallet (Id: ${unLoadingData.value.id}) is undloaded`)
  }
}

provide('unLoading', { resetUnloadingPallet, unloadingPallet, unLoadingIsLoading })

</script>
