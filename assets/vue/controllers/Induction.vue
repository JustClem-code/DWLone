<template>
  <div class="flex flex-col gap-8">
    <!-- <BorderedContent title="Pallets">
      <PalletList v-if="palletsOnFloor" :pallets="palletsOnFloor" />
      <div v-else-if="errorPallet">Error: {{ errorPallet }}</div>
      <div v-else>Loading...</div>
    </BorderedContent> -->

    <Pallet5S :currentPallet="currentPallet"></Pallet5S>

    <BorderedContent title="ASML">
      <div class="flex flex-col gap-4">
        <Conveyor @action="handleAction"></Conveyor>
      </div>
    </BorderedContent>
  </div>
</template>

<script setup>

import { ref, provide, computed } from 'vue'
import BorderedContent from './UI/BorderedContent.vue'
import PalletList from './UnloadingComponents.vue/PalletList.vue'

import { useFetch, usePostFetch } from '../composables/fetch.js'
import emitter from '../composables/eventBus.js'
import Pallet5S from './InductionComponents/Pallet5S.vue'
import Conveyor from './InductionComponents/Conveyor.vue'

const notifier = (type, message, message_2) => {
  emitter.emit('notify', { type: type, message: message, message_2: message_2 })
}

const { data: palletsOnFloor, error: errorPallet } = useFetch('/getpalletsonfloor')

const unLoadingData = ref(null)
const unLoadingError = ref(null)
const unLoadingIsLoading = ref(false)
const addPalletLoading = ref(false)

const currentPallet = ref(null)

const addPallet = (val) => {
  addPalletLoading.value = true
  setTimeout(() => {
    currentPallet.value = val
    addPalletLoading.value = true
    console.log('addPallet', val);
    notifier('success', 'Add Pallet', `The pallet (Id: ${currentPallet.value.id}) is in 5S location`)
    addPalletLoading.value = false
  }, 1000);

 }

provide('unLoading', { unLoadingIsLoading })

provide('induction', { palletsOnFloor, addPalletLoading, addPallet })

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

function handleAction(item) {
  // exemple : on enlève l’item de la liste et on log
  currentPallet.value.packages = currentPallet.value.packages.filter((i) => i.id !== item.id);
  console.log('Action sur', item);
}


</script>
