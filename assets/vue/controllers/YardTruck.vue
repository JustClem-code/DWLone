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

import BorderedContent from './UI/BorderedContent.vue'
import DockCardComponent from './YardTruckComponents/DockCardComponent.vue'
import TruckListComponent from './YardTruckComponents/TruckListComponent.vue'

import { useFetch, usePostFetch } from '../composables/fetch.js'
import { useNotification } from '../composables/eventBus.js'

const { data: docks, error: errorDock } = useFetch('/getdocks')
const { data: trucks, error: errorTruck } = useFetch('/gettrucks')


const { notifier } = useNotification()

const dockingData = ref(null)
const dockingError = ref(null)
const dockingIsLoading = ref(false)

const notDepartedTrucks = computed(() => {
  if (!trucks.value) return
  return trucks.value.filter(truck => truck.departureDate === null);
})


const freeDocks = computed(() => {
  if (!docks.value) return
  return docks.value.filter(dock => dock.truckId === null);
})

const updateListElements = () => {

  const {
    dock,
    previousDock,
    truck
  } = dockingData.value

  const currentTruck = trucks.value.find(t => t.id === truck.id)

  if (!currentTruck) return

  Object.assign(currentTruck, truck)

  if (previousDock) {
    const currentPreviousDock = docks.value.find(d => d.id === previousDock.id)
    if (currentPreviousDock) {
      Object.assign(currentPreviousDock, previousDock)
    }
  }

  if (dock) {
    const newDock = docks.value.find(d => d.id === dock.id)
    if (newDock) {
      Object.assign(newDock, dock)
    }
  }

}

async function resetDockingTruck(truck) {

  dockingIsLoading.value = true;

  const { data, error } = await usePostFetch(`/resetDockingTruck/${truck.id}`)

  if (error.value) {
    setTimeout(() => {
      notifier('error', 'Error rest docking', `${error.value}`)
    }, 1000);
    setTimeout(() => {
      dockingIsLoading.value = false;
      dockingData.value = null;
      return
    }, 1500);
  }

  if (data.value) {
    dockingData.value = data.value;
    updateListElements()
    dockingIsLoading.value = false;
    notifier('success', 'Reset', `The truck (Vrid: ${dockingData.value.truck.name}) is reset`)
  }
}
async function dockingTruck(truck, dock) {

  dockingIsLoading.value = true;

  const { data, error } = await usePostFetch(`/dockingTruck/${truck.id}`, { id: dock.id })

  if (error.value) {
    setTimeout(() => {
      notifier('error', 'Error docking', `${error.value}`)
    }, 1000);
    setTimeout(() => {
      dockingIsLoading.value = false;
      dockingData.value = null;
      return
    }, 1500);
  }

  if (data.value) {
    dockingData.value = data.value;
    updateListElements()
    dockingIsLoading.value = false;
    notifier('success', 'Docking', `The truck (Vrid: ${dockingData.value.truck.name}) docking ${dockingData.value.dock.name}`)
  }
}

async function unDockingTruck(truck) {

  dockingIsLoading.value = true;

  const { data, error } = await usePostFetch(`/unDockingTruck/${truck.id}`)

  if (error.value) {
    setTimeout(() => {
      notifier('error', 'Error unDocking', `${error.value}`)
    }, 1000);
    setTimeout(() => {
      dockingIsLoading.value = false;
      dockingData.value = null;
      return
    }, 1500);
  }

  if (data.value) {
    dockingData.value = data.value;
    updateListElements()
    dockingIsLoading.value = false;
    notifier('success', 'Undocking', `The truck (Vrid: ${dockingData.value.truck.name}) departure from the dock ${dockingData.value.previousDock.name}`)
  }
}

provide('yardTruck', { notDepartedTrucks, trucks, dockingTruck, unDockingTruck, dockingIsLoading })

</script>
