<template>
  <div class="flex flex-col gap-8">
    <BorderedContent title="Pallets">
      <PalletList v-if="palletsOnFloor" :pallets="palletsOnFloor" />
      <div v-else-if="errorPallet">Error: {{ errorPallet }}</div>
      <div v-else>Loading...</div>
    </BorderedContent>
  </div>


  <ul class="space-y-2">
    <li
      v-for="(item, index) in items"
      :key="item.id"
      class="border rounded p-2 cursor-move bg-white"
      draggable="true"
      @dragstart="onDragStart(index)"
      @dragover.prevent
      @drop="onDrop(index)"
    >
      {{ item.label }}
    </li>
  </ul>
</template>

<script setup>

import { ref, provide, computed } from 'vue'
import BorderedContent from './UI/BorderedContent.vue'
import PalletList from './UnloadingComponents.vue/PalletList.vue'

import { useFetch, usePostFetch } from '../composables/fetch.js'
import emitter from '../composables/eventBus.js'

const notifier = (type, message, message_2) => {
  emitter.emit('notify', { type: type, message: message, message_2: message_2 })
}

const { data: palletsOnFloor, error: errorPallet } = useFetch('/getpalletsonfloor')

const unLoadingData = ref(null)
const unLoadingError = ref(null)
const unLoadingIsLoading = ref(false)

provide('unLoading', {unLoadingIsLoading })

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

// DRAG AND DROP

const items = ref([
  { id: 1, label: 'Item A' },
  { id: 2, label: 'Item B' },
  { id: 3, label: 'Item C' }
])

const draggedIndex = ref(null)

function onDragStart(index) {
  draggedIndex.value = index
}

function onDrop(index) {
  if (draggedIndex.value === null || draggedIndex.value === index) return

  const moved = items.value[draggedIndex.value]
  // on enlève l’item
  items.value.splice(draggedIndex.value, 1)
  // on l’insère à la nouvelle position
  items.value.splice(index, 0, moved)
  draggedIndex.value = null
}

</script>
