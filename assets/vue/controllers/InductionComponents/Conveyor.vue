<template>
  <div class="drop-container" @dragover="onDragOver" @drop="onDrop">
    <Package v-if="currentPackage" :package="currentPackage" />
    <div v-else
      class="relative flex flex-col justify-center items-center w-full min-h-60 border-2 border-dashed border-gray-200 dark:border-gray-700/90 rounded-xl p-4 sm:p-8">
      <AddDatabaseIcon size="size-16" color="text-gray-200 dark:text-gray-700/90" />
      <span>Drop the package</span>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useDragStore } from '../../composables/useDragStore.js';
import Package from '../UI/Package.vue';
import AddDatabaseIcon from '../UI/Icons/AddDatabaseIcon.vue';

const emit = defineEmits(['action']);

const { getDraggedItem } = useDragStore();

const currentPackage = ref(null)

function onDragOver(event) {
  event.preventDefault();
  event.dataTransfer.dropEffect = 'move';
}

function onDrop(event) {
  event.preventDefault();
  const item = getDraggedItem();
  if (!item) return;

  currentPackage.value = item

  // 1) appliquer l’action voulue
  emit('action', item);

  // 2) éventuellement nettoyer visuel / store
}
</script>
