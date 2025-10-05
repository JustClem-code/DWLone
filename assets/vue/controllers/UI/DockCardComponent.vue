<template>
  <div class="w-full bg-white border border-0 rounded-md shadow-sm">
    <div class="border-b border-slate-200 p-4">
      <div class="flex justify-between">
        <h3 class="text-base font-semibold">{{ dock.name }}</h3>
        <BadgeComponent :type="!dock.truckWrid ? 'valid' : 'warning'" :title="!dock.truckWrid ? 'Free' : 'Used'" />
      </div>
      <p class="text-sm font-light" :class="{ 'opacity-25': !dock.truckWrid }">
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
