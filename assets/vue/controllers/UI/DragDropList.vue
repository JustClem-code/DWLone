<template>
  <ul class="space-y-2">
    <li v-for="(item, index) in items" :key="item.id" class="border rounded p-2 cursor-move bg-white" draggable="true"
      @dragstart="onDragStart(index)" @dragover.prevent @drop="onDrop(index)">
      {{ item.label }}
    </li>
  </ul>
</template>

<script setup>

import { ref } from 'vue'

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
