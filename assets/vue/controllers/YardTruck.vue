<template>
  <h1 class="text-4xl text-lapis font-bold"> Yard truck</h1>
  <div>
    <ul v-if="docks" class="flex flex-col gap-4">
      <li v-for="dock in docks" key={{dock.id}} class="border border-solid border-white rounded-md">
        <p>{{ dock.name }} - {{ dock.truckWrid ?? 'non défini' }}</p>
        <button @click="dockingTruck(dock.id)">Click Me</button>
      </li>
    </ul>
    <div v-else-if="errorDock">Error: {{ errorDock }}</div>
    <div v-else>Loading...</div>
  </div>
</template>

<script setup>
import { useFetch, usePostFetch } from './fetch.js'

const { data: docks, error: errorDock } = useFetch('/getdocks')
console.log(docks);

function dockingTruck(id) {
  usePostFetch(`/dockingTruck/263`, { id: id})
}


</script>
