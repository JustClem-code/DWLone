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
import emitter from '../composables/eventBus.js'

const notifier = (type, message, message_2) => {
  emitter.emit('notify', { type: type, message: message, message_2: message_2 })
}

const { data: docks, error: errorDock } = useFetch('/getoccupieddocks')

const { data: palletsOnFloor, error: errorPallet } = useFetch('/getpalletsonfloor')

const unLoadingData = ref(null)
const unLoadingError = ref(null)
const unLoadingIsLoading = ref(false)

provide('unLoading', { unloadingPallet, unLoadingIsLoading })

console.log("occupied docks", docks);
console.log("unloaded pallet", palletsOnFloor);


const updateListElements = () => {
  const {
    palletId,
    userId,
    userName
  } = unLoadingData.value

  const palletInTruck = docks.value
    .flatMap(dock => dock.pallets || [])
    .find(pallet => pallet.id === palletId)

  if (!palletInTruck) return

  palletInTruck.userId = userId || null
  palletInTruck.userName = userName || null

  const palletOnFloorIndex = palletsOnFloor.value.findIndex(p => p.id === palletId)

  if (palletOnFloorIndex === -1) {
    palletsOnFloor.value.push({ id: palletId, userId: userId, userName: userName })
  } else {
    palletsOnFloor.value.splice(palletOnFloorIndex, 1)
  }

}



async function unloadingPallet(palletId, reset) {
  unLoadingIsLoading.value = true;

  const { data, error } = await usePostFetch(`/unloadingPallet/${palletId.id}`, { reset: reset ?? false })

  unLoadingData.value = data.value;
  unLoadingError.value = error.value;

  if (unLoadingData.value) {
    updateListElements()
    unLoadingIsLoading.value = false;

    console.log(unLoadingData.value);
    if (reset) {
      notifier('success', 'Reset', `The pallet (Id: ${unLoadingData.value.palletId}) is reset`)
    } else {
      notifier('success', 'Unloading', `The pallet (Id: ${unLoadingData.value.palletId}) is undloaded`)
    }

  }
}


</script>
