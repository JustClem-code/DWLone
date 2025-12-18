<template>

  <div class="flex flex-col gap-4">

    <div v-if="currentPallet"
      class="w-full bg-white dark:bg-gray-800 border border-0 dark:border-1 rounded-md shadow-sm dark:shadow-none dark:border-gray-700/90">
      <div class="flex items-center justify-between py-6 px-8 border-b border-gray-200 dark:border-gray-700/90">
        <div>
          <h2>Pallet</h2>
          <p class="text-xs text-gray-400 mt-2">
            {{ currentPalletIsEmpty ? 'The pallet is empty, change it or stop to induct'
              : 'Pick a package in the pallet and drop it in the zone' }}
          </p>
        </div>
        <div class="flex items-center gap-2">
          <MinimalToggleMenu :items="menuItems" @select="handleMenuAction" />
        </div>
      </div>
      <div class="grid grid-cols-1 sm:grid-cols-2 max-sm:divide-y sm:divide-x divide-gray-200 dark:divide-gray-700/90">
        <div class="py-6 px-8">
          <p class="text-sm text-gray-400">Pallet id</p>
          <p class="text-4xl pt-2">{{ currentPallet.id }}</p>
        </div>
        <div class="py-6 px-8">
          <p class="text-sm text-gray-400">Nb of packages</p>
          <p class="text-4xl pt-2">{{ getNumberOfPackagesNotInducted(currentPallet) }}</p>
        </div>
      </div>
    </div>

    <div v-if="currentPallet && !currentPalletIsEmpty || inductPackageLoading"
      class="relative w-full min-h-70 flex items-center justify-center">
      <transition name="fade-slide" tag="div" enter-active-class="transition-all duration-500 ease-out"
        enter-from-class="opacity-0" enter-to-class="opacity-100">
        <div v-if="currentPallet && !currentPalletIsEmpty && !inductPackageLoading" draggable="true"
          @dragstart="(e) => onDragStart(e, currentPallet.packages[0])" @dragend="onDragEnd()"
          class="absolute top-0 w-full z-10" :class="cursorType">
          <Package :package="currentPallet.packages[0]" borderColor="border-blue-500" />
        </div>
      </transition>
      <p v-if="inductPackageLoading" class="animate-pulse">Wait for another package</p>
    </div>

    <DashedEmptyState v-if="!currentPallet || currentPalletIsEmpty && !inductPackageLoading" @click="selectOptions()"
      :title="currentPallet ? 'Change pallet' : 'Add a pallet'" :disabled="palletsOnfloorOptions?.length === 0">
      <AddDatabaseIcon size="size-16" color="text-gray-200 dark:text-gray-700/90" />
    </DashedEmptyState>
  </div>

  <DialogComponentSlot ref="SelectOptionRef">
    <SelectOptionComponent :options="palletsOnfloorOptions" :isLoading="addPalletLoading"
      @submitOption="val => addPallet(val.selected)" @closeDialog="SelectOptionRef?.closeDialog()" />
  </DialogComponentSlot>

  <DialogComponentSlot ref="infoDialogRef" :hasCloseCross="true">
    <PalletInfo :currentPallet="currentPallet" />
  </DialogComponentSlot>
  <DialogComponentSlot ref="confirmResetDialogRef">
    <ConfirmationComponent question="Are you sure to reset ?" @confirm="resetItem"
      @cancel="confirmResetDialogRef?.closeDialog()" />
  </DialogComponentSlot>

</template>

<script setup>
import { inject, ref, computed } from 'vue'
import { useDragStore } from '../../composables/useDragStore.js';

import AddDatabaseIcon from '../UI/Icons/AddDatabaseIcon.vue';
import DialogComponentSlot from '../UI/Modals/DialogComponentSlot.vue';
import SelectOptionComponent from '../UI/Modals/SelectOptionComponent.vue';
import Package from '../UI/Package.vue';
import MinimalToggleMenu from '../UI/MinimalToggleMenu.vue';
import ConfirmationComponent from '../UI/Modals/ConfirmationComponent.vue';
import DashedEmptyState from '../UI/DashedEmptyState.vue';

import PalletInfo from '../SharedComponents/PalletInfo.vue';

const props = defineProps({
  currentPallet: Object,
  currentPackage: Object
});

const { palletsOnfloorOptions, addPalletLoading, addPallet, setLocationLoading, resetLocationsBagsPackages, getNumberOfPackagesNotInducted } = inject('induction')

const SelectOptionRef = ref(null)

const infoDialogRef = ref(null);

const confirmResetDialogRef = ref(null);

const isDragging = ref(false)

const currentPalletIsEmpty = computed(() => {
  return props.currentPallet ? getNumberOfPackagesNotInducted(props.currentPallet) === 0 : null
})

const inductPackageLoading = computed(() => {
  return isDragging.value || props.currentPackage || setLocationLoading.value
})

const cursorType = computed(() => {
  if (props.currentPackage) {
    return 'cursor-not-allowed'
  }
  return isDragging.value ? 'cursor-grabbing' : 'cursor-grab'
})

const { setDraggedItem } = useDragStore();

function onDragStart(event, item) {
  isDragging.value = true;
  document.documentElement.classList.add("grabbing");
  setDraggedItem(item);
  event.dataTransfer.effectAllowed = 'move';
}

function onDragEnd() {
  isDragging.value = false
  document.documentElement.classList.remove("grabbing");
}

const menuItems = computed(() => [
  {
    label: 'Infos',
    action: 'openInfos',
  },
  {
    label: 'Change',
    action: 'selectOptions',
    isDisabled:
      palletsOnfloorOptions.value?.length === 0
  },
  {
    label: 'Hard Reset',
    action: 'confirmResetItem',
  },
])

const selectOptions = () => SelectOptionRef.value?.openDialog()

const openInfos = () => infoDialogRef.value?.openDialog()

const resetItem = () => {
  resetLocationsBagsPackages()
  confirmResetDialogRef.value?.closeDialog()
}

const confirmResetItem = () => confirmResetDialogRef.value?.openDialog()

const handleMenuAction = (action) => {
  const actions = { selectOptions, confirmResetItem, openInfos }
  if (actions[action]) actions[action]()
}
</script>

<style scoped>
html.grabbing * {
  cursor: grabbing !important;
}
</style>
