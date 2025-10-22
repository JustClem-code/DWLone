<template>
  <div
    class="w-full bg-white dark:bg-gray-800/50 border border-0 dark:border-1 rounded-md shadow-sm dark:shadow-none dark:border-gray-700/90"
    :class="{ 'animate-pulse': !dock }">
    <div class="border-b border-gray-300 dark:border-gray-700/90 p-4">
      <div class="flex justify-between">
        <h3 class="text-base font-semibold">{{ dock?.name || 'Dock name' }}</h3>
        <BadgeComponent :type="badgeType" :title="badgeTitle" />
      </div>
      <p class="text-sm font-light text-gray-800 dark:text-gray-400"
        :class="{ 'opacity-25': !dock?.truckWrid }">
        {{ dock?.truckWrid ?? 'No truck' }}
      </p>
    </div>
    <div v-if="!dock" class="w-full flex justify-center p-2 text-gray-800 dark:text-gray-400 disabled:opacity-25">
      <span>Select</span>
    </div>
    <SelectDialogComponent v-else title="Trucks" :options="trucks" :disabled="!dock.truckWrid" styleColorButton="flat"
      @submitOption="val => dockingTruck(val.selected, dock)" />
  </div>
</template>

<script setup>
import { inject, computed } from 'vue'
import BadgeComponent from './BadgeComponent.vue';
import SelectDialogComponent from './SelectDialogComponent.vue';

const { trucks, dockingTruck, dockingIsLoading } = inject('yardTruck')
const props = defineProps({
  dock: Object
})

const badgeType = computed(() => {
  if (!props.dock) return null;
  return !props.dock.truckWrid ? 'valid' : 'warning';
})

const badgeTitle = computed(() => {
  if (!props.dock) return null;
  return !props.dock.truckWrid ? 'Free' : 'Used';
})


</script>
