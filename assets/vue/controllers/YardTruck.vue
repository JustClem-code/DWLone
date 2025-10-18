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
      <TruckListComponent v-if="trucks" :trucks="trucks" :docks="docks" />
      <div v-else-if="errorTruck">Error: {{ errorTruck }}</div>
      <div v-else>Loading...</div>
    </BorderedContent>
  </div>
</template>

<script setup>

import { ref, provide } from 'vue'
import { useFetch, usePostFetch } from '../composables/fetch.js'
import SelectDialogComponant from './UI/SelectDialogComponent.vue'
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


function updateListElements() {
  const truck = trucks.value.find(item => item.id === dockingData.value.truckId);

  const newDock = docks.value.find(item => item.id === dockingData.value.dockId);

  const previousDock = docks.value.find(item => item.id === dockingData.value.previousDockId);
  truck.dock = dockingData.value.dockName
  newDock.truckWrid = dockingData.value.truckWrid
  previousDock.truckWrid = null
}

async function dockingTruck(truckId, dockId) {

  console.log('debug truckId', truckId);
  console.log('debug dockId', dockId);
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
