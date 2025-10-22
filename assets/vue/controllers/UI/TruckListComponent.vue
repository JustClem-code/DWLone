<template>
  <ul role="list" class="divide-y">
    <li v-for="truck in trucks" :key=truck.id
      class="flex items-center justify-between py-4 border-gray-200 dark:border-gray-700/90">
      <div class="">
        <div class="flex items-center gap-4 text-base font-semibold">
          <p>{{ truck.wrid }} - {{ truck.dock ?? 'Waiting dock' }}</p>
          <BadgeComponent :type="badgeType(truck)" title="badgeTitle" />
        </div>
        <p class="text-sm font-light text-gray-800 dark:text-gray-400">date</p>
      </div>
      <div>
        <SelectDialogComponent title="Docks" :options="docks" :isNotDocked="!truck.dock"
          :disabled="true" styleColorButton="primary" @submitOption="val => dockingTruck(truck, val.selected)" />
      </div>
    </li>
  </ul>
</template>

<script setup>
import { inject, computed } from 'vue'
import BadgeComponent from './BadgeComponent.vue';
import SelectDialogComponent from './SelectDialogComponent.vue';

const { trucks, dockingTruck, dockingIsLoading } = inject('yardTruck')
const props = defineProps({
  trucks: Array,
  docks: Array,
})

function badgeType (truck) {
  return truck.dock ? 'valid' : 'warning';
}
/*
const badgeType = computed(() => {
  if (!props.dock) return null;
  return !props.dock.truckWrid ? 'valid' : 'warning';
})

const badgeTitle = computed(() => {
  if (!props.dock) return null;
  return !props.dock.truckWrid ? 'Free' : 'Used';
}) */

</script>
