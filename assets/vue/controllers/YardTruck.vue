<template>
  <h1 class="text-4xl text-lapis font-bold"> Yard truck</h1>
  <div>
    <div v-if="docks">
      <div v-for="dock in docks" key="dock.id">
        <p>{{ dock.name }} - {{ dock.truckId ?? 'non défini' }}</p>
      </div>
    </div>
    <div v-else-if="errorDock">Error: {{ errorDock }}</div>
    <div v-else>Loading...</div>
  </div>
</template>

<script setup>
import { useFetch } from './fetch.js'

const { data: docks, error: errorDock } = useFetch('/getdocks')

</script>
