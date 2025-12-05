<template>
  <div class="flex flex-col gap-8">
    <Pallet5S :currentPallet="currentPallet"></Pallet5S>

    <BorderedContent title="ASML">
      <div class="flex flex-col gap-4">
        <Conveyor @action="handleAction"></Conveyor>
      </div>
    </BorderedContent>
  </div>
</template>

<script setup>

import { ref, provide, computed, watch, onMounted } from 'vue'
import BorderedContent from './UI/BorderedContent.vue'

import { useFetch, usePostFetch } from '../composables/fetch.js'
import emitter from '../composables/eventBus.js'
import Pallet5S from './InductionComponents/Pallet5S.vue'
import Conveyor from './InductionComponents/Conveyor.vue'

const notifier = (type, message, message_2) => {
  emitter.emit('notify', { type: type, message: message, message_2: message_2 })
}

const { data: palletsOnFloor, error: errorPallet } = useFetch('/getpalletsonfloor')

const addPalletLoading = ref(false)
const setLocationLoading = ref(false)

const STORAGE_KEY = 'currentPallet'

const currentPallet = ref(null)

onMounted(() => {
  const raw = localStorage.getItem(STORAGE_KEY)
  currentPallet.value = raw ? JSON.parse(raw) : null
  console.log('current pallet onmounter', currentPallet.value);

})


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

async function setLocation(packageId) {
  setLocationLoading.value = true;

  const { data, error } = await usePostFetch(`/setLocation/${truckId.id}`)
  dockingData.value = null;
  dockingError.value = null;

  dockingData.value = data.value;
  dockingError.value = error.value;

  if (dockingData.value) {
    updateListElements()
    dockingIsLoading.value = false;

    console.log(dockingData.value);
    if (reset) {
      notifier('success', 'Reset', `The truck (Vrid: ${dockingData.value.truckName}) is reset`)
    } else if (!dockingData.value.dockId && dockingData.value.previousDockId) {
      notifier('success', 'Undocking', `The truck (Vrid: ${dockingData.value.truckName}) departure from the dock ${dockingData.value.previousDockName}`)
    } else {
      notifier('success', 'Docking', `The truck (Vrid: ${dockingData.value.truckName}) docking ${dockingData.value.dockName}`)
    }

  }
}


const handleAction = (item) => {
  // exemple : on enlève l’item de la liste et on log
  currentPallet.value.packages = currentPallet.value.packages.filter((i) => i.id !== item.id);
  console.log('Action sur', item);
}

watch(
  currentPallet,
  (val) => {
    if (val === null) {
      localStorage.removeItem(STORAGE_KEY)
    } else {
      localStorage.setItem(STORAGE_KEY, JSON.stringify(val))
    }
  },
  { deep: true }
)


</script>
