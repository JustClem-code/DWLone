<template>
  <div class="w-full bg-white dark:bg-gray-800/50 border border-0 dark:border-1 rounded-md shadow-sm dark:shadow-none dark:border-gray-700/90">
    <div class="border-b border-gray-300 dark:border-gray-700/90 p-4">
      <div class="flex justify-between">
        <h3 class="text-base font-semibold">{{ dock.name }}</h3>
        <BadgeComponent :type="!dock.truckWrid ? 'valid' : 'warning'" :title="!dock.truckWrid ? 'Free' : 'Used'" />
      </div>
      <p class="text-sm font-light text-gray-800 dark:text-gray-400" :class="{ 'opacity-25': !dock.truckWrid }">
        {{ dock.truckWrid ?? 'No truck' }}
      </p>
    </div>
    <SelectDialogComponent title="Trucks" :options="trucks" :disabled="!dock.truckWrid"
      @submitOption="val => dockingTruck(val.selected, dock)" />
  </div>
</template>

<script setup>
import { inject } from 'vue'
import BadgeComponent from './BadgeComponent.vue';
import SelectDialogComponent from './SelectDialogComponent.vue';

const { trucks, dockingTruck } = inject('yardTruck')
const props = defineProps({
  dock: Object,
})

</script>
