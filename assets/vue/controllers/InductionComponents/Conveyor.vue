<template>
  <div class="drop-container relative" @dragover="onDragOver" @drop="onDrop">
    <transition name="fade-slide" tag="div" leave-active-class="transition-all duration-500 ease-in"
      leave-from-class="opacity-100 translate-y-0" leave-to-class="opacity-0 translate-y-4">
      <div v-if="currentPackage" class="absolute top-0 w-full">
        <Package :package="currentPackage" :loading="setLocationLoading" />
      </div>
    </transition>
    <div
      class="flex flex-col justify-center items-center w-full min-h-70 border-2 border-dashed border-gray-200 dark:border-gray-700/90 rounded-md p-4 sm:p-8">
      <AddDatabaseIcon size="size-16" color="text-gray-200 dark:text-gray-700/90" />
      <span>Drop the package</span>
    </div>
  </div>
</template>

<script setup>
import { inject } from 'vue';
import { useDragStore } from '../../composables/useDragStore.js';

import Package from '../UI/Package.vue';
import AddDatabaseIcon from '../UI/Icons/AddDatabaseIcon.vue';

const props = defineProps({
  currentPackage: Object
});

const { setLocation, setLocationLoading } = inject('induction')

const { getDraggedItem } = useDragStore();

function onDragOver(event) {
  event.preventDefault();
  event.dataTransfer.dropEffect = 'move';
}

function onDrop(event) {
  event.preventDefault();
  const item = getDraggedItem();
  if (!item) return;

  setLocation(item)
}
</script>
