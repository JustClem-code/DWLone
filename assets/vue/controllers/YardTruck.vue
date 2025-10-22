<template>
  <div class="flex flex-col gap-8">
    <BorderedContent title="Docks">
      <div v-if="docks" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
        <DockCardComponent v-for="dock in docks" :key=dock.id :dock="dock" />
      </div>
      <div v-else-if="errorDock">Error: {{ errorDock }}</div>
      <div v-else class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
        <DockCardComponent v-for="i in 12" />
      </div>
    </BorderedContent>
    <BorderedContent title="Trucks">
      <TruckListComponent v-if="trucks" :trucks="trucks" :docks="freeDocks" />
      <div v-else-if="errorTruck">Error: {{ errorTruck }}</div>
      <div v-else>Loading...</div>
    </BorderedContent>
  </div>
</template>

<script setup>

import { ref, provide, computed } from 'vue'
import { useFetch, usePostFetch } from '../composables/fetch.js'
import DockCardComponent from './UI/DockCardComponent.vue'
import BorderedContent from './UI/BorderedContent.vue'
import TruckListComponent from './UI/TruckListComponent.vue'

const { data: docks, error: errorDock } = useFetch('/getdocks')
const { data: trucks, error: errorTruck } = useFetch('/gettrucks')

const dockingData = ref(null)
const dockingError = ref(null)
const dockingIsLoading = ref(false)

provide('yardTruck', { trucks, dockingTruck, dockingIsLoading })

console.log("trucks", trucks);
console.log("docks", docks);


const freeDocks = computed(() => {
  if (!docks.value) return
  return docks.value.filter(dock => dock.truckId === null);
})

function updateListElements() {
  const { truckId, previousDockId, dockId, dockName, truckWrid } = dockingData.value

  const truck = trucks.value.find(t => t.id === truckId)

  if (!truck) return

  if (previousDockId) {
    const previousDock = docks.value.find(d => d.id === previousDockId)
    if (previousDock) {
      previousDock.truckWrid = null
      previousDock.truckId = null
      console.log('previousDock', previousDock)
    }
  }

  if (dockId) {
    const newDock = docks.value.find(d => d.id === dockId)
    if (newDock) {
      newDock.truckWrid = truckWrid
      newDock.truckId = truckId
      console.log('newDock', newDock)
    }
  }

  truck.dock = dockName || null
}

async function dockingTruck(truckId, dockId) {
  dockingIsLoading.value = true;

  const { data, error } = await usePostFetch(`/dockingTruck/${truckId.id}`, { id: dockId?.id ?? null })
  dockingData.value = null;
  dockingError.value = null;

  dockingData.value = data.value;
  dockingError.value = error.value;

  if (dockingData.value) {
    console.log('dockingData', dockingData.value);
    updateListElements()
    dockingIsLoading.value = false;
  }

  console.log('dockingErrorValue', dockingError.value);

}

</script>
