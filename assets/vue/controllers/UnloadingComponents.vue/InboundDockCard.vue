<template>
  <div
    class="w-full bg-white dark:bg-gray-800/50 border border-0 dark:border-1 rounded-md shadow-sm dark:shadow-none dark:border-gray-700/90"
    :class="{ 'animate-pulse': !dock }">
    <div class="border-b border-gray-300 dark:border-gray-700/90 p-4">
      <div class="flex justify-between">
        <h3 class="text-base font-semibold">{{ dock?.name || 'Dock name' }}</h3>
        <BadgeComponent :type="badgeType" :title="badgeTitle" />
      </div>
      <p class="text-sm font-light text-gray-800 dark:text-gray-400" :class="{ 'opacity-25': !dock?.truckName }">
        {{ dock?.truckName ?? 'No truck' }}
      </p>
    </div>
    <div v-if="!dock" class="w-full flex justify-center p-2 text-gray-800 dark:text-gray-400 disabled:opacity-25">
      <span>Select</span>
    </div>

    <BaseButton title="Pallets" styleColor="flat" :isDisabled="notUnloadedPallets?.length === 0" @click="SelectOptionRef?.openDialog()" />

    <DialogComponentSlot ref="SelectOptionRef">
      <SelectOptionComponent :options="notUnloadedPallets" :isLoading="unLoadingIsLoading"
          @submitOption="val => unloadingPallet(val.selected, dock)" @closeDialog="SelectOptionRef?.closeDialog()"/>
    </DialogComponentSlot>
  </div>
</template>

<script setup>
import { inject, ref, computed } from 'vue'
import BadgeComponent from '../UI/BadgeComponent.vue';
import BaseButton from '../UI/Buttons/BaseButton.vue';
import DialogComponentSlot from '../UI/Modals/DialogComponentSlot.vue';
import SelectOptionComponent from '../UI/Modals/SelectOptionComponent.vue';

const { notUnloadedPallets, unloadingPallet, unLoadingIsLoading } = inject('unLoading')
const props = defineProps({
  dock: Object
})

const SelectOptionRef = ref(null)

const badgeType = computed(() => {
  if (!props.dock) return null;
  return !props.dock.truckName ? 'valid' : 'warning';
})

const badgeTitle = computed(() => {
  if (!props.dock) return null;
  return !props.dock.truckName ? 'Free' : 'Used';
})


</script>
