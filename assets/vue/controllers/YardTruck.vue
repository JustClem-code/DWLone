<template>
  <h1 class="text-4xl text-lapis font-bold"> Yard truck</h1>
  <div class="flex gap-4">
    <div class="w-full">
      <ul v-if="docks" class="flex flex-col gap-4">
        <li v-for="dock in docks" :key=dock.id class="border border-solid border-white rounded-md">
          <p>{{ dock.name }} - {{ dock.truckWrid ?? 'non défini' }}</p>
          <button @click="dockingTruck(dock.id)">Click Me</button>
        </li>
      </ul>
      <div v-else-if="errorDock">Error: {{ errorDock }}</div>
      <div v-else>Loading...</div>
    </div>
    <div class="w-full">
      <ul v-if="trucks" class="flex flex-col gap-4">
        <li v-for="truck in trucks" :key=truck.id class="border border-solid border-white rounded-md">
          <p>{{ truck.wrid }} - {{ truck.dock ?? 'non défini' }}</p>
          <select @change="submitOption($event, truck)"
            class="form-select block w-full mt-1 rounded border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
            <option disabled value="">Choisir…</option>
            <option v-for="dock in docks" :value="dock.id">{{ dock.name }}</option>
          </select>
        </li>
      </ul>
      <div v-else-if="errorTruck">Error: {{ errorTruck }}</div>
      <div v-else>Loading...</div>
    </div>
  </div>
  <div v-if="dockingData">{{ dockingData }}</div>
  <div v-if="dockingError">{{ dockingError }}</div>
</template>

<script setup>

//TODO: -gérer la réponse de docking côté front
//      -compartimenter les composants
//.     -revoir les couleurs et l'UI (beurk)

import { ref, watch, onMounted } from 'vue'

import { useFetch, usePostFetch } from './fetch.js'

const { data: docks, error: errorDock } = useFetch('/getdocks')
const { data: trucks, error: errorTruck } = useFetch('/gettrucks')

const dockingData = ref(null)
const dockingError = ref(null)

console.log("trucks", trucks);
console.log("docks", docks);



async function dockingTruck(truckId, dockId) {

  dockingData.value = null;
  dockingError.value = null;

  const { data, error } = await usePostFetch(`/dockingTruck/${truckId}`, { id: dockId })

  dockingData.value = data.value;
  dockingError.value = error.value;

  console.log('dockingData', dockingData.value);
  console.log('dockingErrorValue', dockingError.value);

}

function submitOption(event, item) {
  const selected = event.target.value

  dockingTruck(item.id, selected)
  console.log("dock selected", selected);
}
</script>
