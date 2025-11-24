<template>
  <div class="flex flex-col gap-8">
    <BorderedContent title="Docks">
      <!-- <div v-if="docks" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
        <DockCardComponent v-for="dock in docks" :key=dock.id :dock="dock" />
      </div>
      <div v-else-if="errorDock">Error: {{ errorDock }}</div>
      <div v-else class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
        <DockCardComponent v-for="i in 12" />
      </div> -->
    </BorderedContent>
  </div>
</template>

<script setup>

import { ref, provide, computed } from 'vue'
import BorderedContent from './UI/BorderedContent.vue'
// import DockCardComponent from './YardTruckComponents/DockCardComponent.vue'

import { useFetch, usePostFetch } from '../composables/fetch.js'
import emitter from '../composables/eventBus.js'

const notifier = (type, message, message_2) => {
  emitter.emit('notify', { type: type, message: message, message_2: message_2 })
}

const { data: docks, error: errorDock } = useFetch('/getdocks')


/* const notDepartedTrucks = computed(() => {
  if (!trucks.value) return
  return trucks.value.filter(truck => truck.departureDate === null);
}) */

/* provide('yardTruck', { notDepartedTrucks }) */

console.log("docks", docks);
 //console.log("notDepartedTrucks", notDepartedTrucks);


const busyDocks = computed(() => {
  if (!docks.value) return
  return docks.value.filter(dock => dock.truckId !== null);
})

/* const updateListElements = () => {
  const {
    truckId,
    previousDockId,
    dockId,
    dockName,
    truckName,
    deliveryDate,
    userDelDate,
    departureDate,
    userDepDate
  } = dockingData.value

  const truck = trucks.value.find(t => t.id === truckId)

  if (!truck) return

  if (previousDockId) {
    const previousDock = docks.value.find(d => d.id === previousDockId)
    if (previousDock) {
      previousDock.truckName = null
      previousDock.truckId = null
    }
  }

  if (dockId) {
    const newDock = docks.value.find(d => d.id === dockId)
    if (newDock) {
      newDock.truckName = truckName
      newDock.truckId = truckId
    }
  }

  truck.dock = dockName || null
  truck.deliveryDate = deliveryDate || null
  truck.userDelDate = userDelDate || null
  truck.departureDate = departureDate || null
  truck.userDepDate = userDepDate || null
}

async function dockingTruck(truckId, dockId, reset) {
  dockingIsLoading.value = true;

  const { data, error } = await usePostFetch(`/dockingTruck/${truckId.id}`, { id: dockId?.id ?? null, reset: reset ?? false })
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

 */
</script>
