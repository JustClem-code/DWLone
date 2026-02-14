<template>
  <dialog ref="myDialog" @click.self="closeDialog">
    <OverlayInvisible @click="closeDialog" />
    <div
      class="text-gray-900 dark:text-gray-100 border border-0 dark:border-1 dark:border-gray-700/90 sm:w-full md:w-max max-w-[95vw] max-h-[95vh] fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-10 p-8 rounded-2xl shadow-2xl"
      :class="darkerBackground ? 'bg-white dark:bg-gray-900' : 'bg-white dark:bg-gray-800'">
      <slot></slot>
      <div v-show="hasCloseCross" class="absolute top-2 right-2 block p-3">
        <IconButton @click="closeDialog">
          <CrossIcon />
        </IconButton>
      </div>
    </div>
  </dialog>
</template>

<script setup>
import { ref } from 'vue';
import OverlayInvisible from '../OverlayInvisible.vue';
import IconButton from '../Buttons/IconButton.vue';
import CrossIcon from '../Icons/CrossIcon.vue';

const props = defineProps({
  hasCloseCross: Boolean,
  darkerBackground: Boolean,
})

const myDialog = ref(null);

const openDialog = () => {
  myDialog.value?.showModal();
};

const closeDialog = () => {
  myDialog.value?.close();
};

defineExpose({ openDialog, closeDialog });
</script>
