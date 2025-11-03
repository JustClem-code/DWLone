<!-- InfoDialogComponentSlot -->
<template>
  <dialog ref="myDialog" @click.self="closeDialog">
    <OverlayInvisible @click="closeDialog" />
    <div
      class="bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 border border-0 dark:border-1 dark:border-gray-700/90 max-w-[95vw] max-h-[95vh] fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-10 p-8 rounded-2xl shadow-2xl">
      <slot></slot>
      <div class="absolute top-2 right-2 block p-3">
        <button class="flex items-center text-gray-300 hover:text-gray-900 dark:text-gray-700 dark:hover:text-gray-100" @click="closeDialog">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
            stroke="currentColor" class="size-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
    </div>
  </dialog>
</template>

<script setup>
import { ref } from 'vue';
import OverlayInvisible from './OverlayInvisible.vue';

const myDialog = ref(null);

const openDialog = () => {
  myDialog.value?.showModal();
};

const closeDialog = () => {
  myDialog.value?.close();
};

defineExpose({ openDialog });
</script>
