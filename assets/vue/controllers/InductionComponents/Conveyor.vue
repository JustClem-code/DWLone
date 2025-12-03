<!-- DropContainer.vue -->
<script setup>
import { useDragStore } from '../../composables/useDragStore.js';

const emit = defineEmits(['action']); // pour informer le parent

const { getDraggedItem } = useDragStore();

function onDragOver(event) {
  // nécessaire pour autoriser le drop
  event.preventDefault();
  event.dataTransfer.dropEffect = 'move';
}

function onDrop(event) {
  event.preventDefault();
  const item = getDraggedItem();
  if (!item) return;

  // 1) appliquer l’action voulue
  emit('action', item);

  // 2) éventuellement nettoyer visuel / store
}
</script>

<template>
  <div
    class="drop-container"
    @dragover="onDragOver"
    @drop="onDrop"
  >
    Dépose ici pour appliquer l’action
  </div>
</template>
