<template>
  <div class="flex flex-col gap-8">
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
import PalletList from './UnloadingComponents.vue/PalletList.vue'

import { useFetch, usePostFetch } from '../composables/fetch.js'
import emitter from '../composables/eventBus.js'

const notifier = (type, message, message_2) => {
  emitter.emit('notify', { type: type, message: message, message_2: message_2 })
}

const { data: palletsOnFloor, error: errorPallet } = useFetch('/getpalletsonfloor')

const unLoadingData = ref(null)
const unLoadingError = ref(null)
const unLoadingIsLoading = ref(false)

provide('unLoading', {unLoadingIsLoading })

console.log("unloaded pallet", palletsOnFloor);


/* const updateListElements = () => {

  const palletInTruck = docks.value
    .flatMap(dock => dock.pallets || [])
    .find(pallet => pallet.id === unLoadingData.value.id)

  if (!palletInTruck) return

  palletInTruck.userId = unLoadingData.value.userId || null
  palletInTruck.userName = unLoadingData.value.userName || null

  const palletOnFloorIndex = palletsOnFloor.value.findIndex(p => p.id === unLoadingData.value.id)

  if (palletOnFloorIndex === -1) {
    palletsOnFloor.value.push(unLoadingData.value)
  } else {
    palletsOnFloor.value.splice(palletOnFloorIndex, 1)
  }

} */

</script>
