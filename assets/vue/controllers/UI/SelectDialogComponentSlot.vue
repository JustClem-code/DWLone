<template>
  <!-- Slot activator -->
  <div @click="openDialog">
    <slot name="activator">
      <!-- Default Button if slot is empty -->
      <BaseButton title="Option" styleColor="empty" :isDisabled="true" />
    </slot>
  </div>

  <!-- In parent component -->
  <!-- <SelectDialogComponentSlot :options="myOptions" v-slot:activator>
    <button class="btn" type="button">Ouvrir le dialog</button>
  </SelectDialogComponentSlot> -->


  <dialog ref="myDialog" @click.self="closeDialog">
    <OverlayInvisible @click="closeDialog" />
    <div
      class="bg-white dark:bg-gray-800 border border-0 dark:border-1 dark:border-gray-700/90 max-w-[95vw] max-h-[95vh] fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-10 p-8 rounded-2xl shadow-2xl">
      <div class="flex">
        <form @submit.prevent="submitOp" class="flex flex-col gap-2 min-w-60">
          <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-2">
            <button v-for="option in options" :key="option.id" type="button" @click="selectOption(option)"
              class="w-full text-base inset-ring inset-shadow-sm transition duration-150 ease-in-out inset-ring-gray-100 hover:inset-ring-gray-300 text-gray-900 dark:text-gray-100 hover:text-blue-500 p-2 rounded-md"
              :class="{ 'inset-ring-2 inset-ring-slate-500 text-blue-700 hover:inset-ring-slate-500 hover:text-blue-700': selected?.id === option.id }">
              {{ option.name ?? 'option' }}
            </button>
          </div>
          <div class="flex gap-2">
            <BaseButton title="Cancel" styleColor="empty" @click="closeDialog" />
            <BaseButton buttonType="submit" title="Submit" styleColor="primary" :isDisabled="!selected || isLoading"
              :isLoading="isLoading" />
          </div>
        </form>
      </div>
    </div>
  </dialog>
</template>

<script setup>
import { ref, watch } from 'vue';
import BaseButton from './Buttons/BaseButton.vue';
import OverlayInvisible from './OverlayInvisible.vue';

const props = defineProps({
  options: Array,
  isLoading: Boolean
});

const emit = defineEmits(['submitOption']);

const myDialog = ref(null);
const shouldCloseOnLoad = ref(false);

const openDialog = () => {
  myDialog.value?.showModal();
};

const closeDialog = () => {
  myDialog.value?.close();
  selected.value = null;
};

const selected = ref(null);

function selectOption(option) {
  selected.value = option;
}

function submitOp() {
  emit('submitOption', { selected: selected.value });
  shouldCloseOnLoad.value = true;
}

watch(() => props.isLoading, (val) => {
  if (val === false && shouldCloseOnLoad.value) {
    closeDialog();
    shouldCloseOnLoad.value = false;
  }
});
</script>
