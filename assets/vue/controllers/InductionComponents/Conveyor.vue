<template>
  <div
    class="drop-container"
    @dragover="onDragOver"
    @drop="onDrop"
  >
    Dépose ici pour appliquer l’action
  </div>
</template>

<script setup>
import { useDragStore } from '../../composables/useDragStore.js';

const emit = defineEmits(['action']);

const { getDraggedItem } = useDragStore();

function onDragOver(event) {
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
