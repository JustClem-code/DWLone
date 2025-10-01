<template>
  <h1 class="text-4xl text-blue-500 font-bold"> Yard truck</h1>

  <div class="flex flex-col p-8 gap-4">

    <div class="w-full border border-solid border-slate-300 rounded-xl overflow-auto no-scrollbar p-8">
      <ul v-if="docks" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 p-2">
        <li v-for="dock in docks" :key=dock.id class="w-full bg-white border border-0 rounded-md">
          <div class="border-b border-slate-50 p-4">
            <h3 class="text-base font-semibold">{{ dock.name }}</h3>
            <p class="text-sm font-light">{{ dock.truckWrid ?? 'No truck' }}</p>
          </div>
          <SelectDialogComponant title="Trucks" :options="trucks" @submitOption="val => dockingTruck(val.selected, dock)"/>
        </li>
      </ul>
      <div v-else-if="errorDock">Error: {{ errorDock }}</div>
      <div v-else>Loading...</div>
    </div>

    <div class="w-full">
      <ul v-if="trucks" class="flex flex-col gap-4">
        <li v-for="truck in trucks" :key=truck.id class="border border-solid border-white rounded-md">
          <p>{{ truck.wrid }} - {{ truck.dock ?? 'Waiting dock' }}</p>
          <SelectDialogComponant title="Docks" :options="docks" @submitOption="val => dockingTruck(truck, val.selected)"/>
        </li>
      </ul>
      <div v-else-if="errorTruck">Error: {{ errorTruck }}</div>
      <div v-else>Loading...</div>
    </div>
  </div>
  <div v-if="dockingData">{{ dockingData.dockId }}</div>
  <div v-if="dockingError">{{ dockingError }}</div>
</template>

<script setup>

//TODO:
// - terminer la modal de dialog, fermer avec un clic externe, bloquer le fond
// - créer une chips d'état "free" et "occupe" et désactiver le bouton
// - gérer la réponse de docking côté front
// - truck name au lieu de truck wrid ?
// - compartimenter les composants
// - revoir les couleurs et l'UI (beurk)

import { ref } from 'vue'
import { useFetch, usePostFetch } from './fetch.js'
import SelectDialogComponant from './UI/SelectDialogComponant.vue'


const { data: docks, error: errorDock } = useFetch('/getdocks')
const { data: trucks, error: errorTruck } = useFetch('/gettrucks')

const dockingData = ref(null)
const dockingError = ref(null)

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


  const { data, error } = await usePostFetch(`/dockingTruck/${truckId.id}`, { id: dockId.id })
  dockingData.value = null;
  dockingError.value = null;

  dockingData.value = data.value;
  dockingError.value = error.value;

  if (dockingData.value) {
    console.log('dockingData', dockingData.value);
    updateListElements()
  }

  console.log('dockingErrorValue', dockingError.value);

}

</script>
