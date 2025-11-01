<template>
  <dialog ref="myDialog" @click.self="closeDialog">
    <OverlayInvisible @click="closeDialog" />
    <div
      class="bg-white dark:bg-gray-800 text-gray-900 dark:text-white border border-0 dark:border-1 dark:border-gray-700/90 max-w-[95vw] max-h-[95vh] fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-10 p-8 rounded-2xl shadow-2xl">
      <slot></slot>
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
