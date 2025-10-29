<template>
  <ul role="list" class="divide-y">
    <li v-for="truck in trucks" :key=truck.id
      class="flex items-center justify-between py-4 border-gray-200 dark:border-gray-700/90">
      <div class="">
        <div class="flex items-center gap-4 text-base font-semibold">
          <p>{{ truck.name }}</p>
          <BadgeComponent :type="badgeType(truck)" :title="badgeTitle(truck)" size="sm" />
        </div>
        <p class="text-sm font-light text-gray-800 dark:text-gray-400">{{ dateInfo(truck) }}</p>
      </div>
      <div class="flex items-center gap-2">
        <SelectDialogComponentSlot v-slot:activator :options="docks" :isLoading="dockingIsLoading"
          @submitOption="val => dockingTruck(truck, val.selected)">
          <BaseButton title="Docks" styleColor="empty" size="sm" :isDisabled="false" />
        </SelectDialogComponentSlot>
        <MinimalToggleMenu :items="menuItems" @click="defineCurrentTruck(truck)" @select="handleMenuAction" />
      </div>
    </li>
  </ul>
</template>

<script setup>
import { inject, ref, toRef, computed } from 'vue'
import BadgeComponent from './BadgeComponent.vue';
import MinimalToggleMenu from './MinimalToggleMenu.vue';
import SelectDialogComponentSlot from './SelectDialogComponentSlot.vue';
import BaseButton from './BaseButton.vue';

import { formattedDateFr } from '../../composables/dateFormat.js'

const { trucks, dockingTruck } = inject('yardTruck')

const yardTruck = inject('yardTruck')
const dockingIsLoading = toRef(yardTruck, 'dockingIsLoading')

const props = defineProps({
  trucks: Array,
  docks: Array,
})

const currentTruck = ref(null)

function badgeType(truck) {
  if (truck.departureDate ) {
    return 'danger'
  }
  return truck.dock ? 'warning' : 'valid';
}

function badgeTitle(truck) {
  if (truck.departureDate ) {
    return 'finish'
  }
  return truck.dock ?? 'Waiting dock'
}

function dateInfo(truck) {
  if (truck.departureDate) {
    return formattedDateFr(truck.departureDate)
  }
  return formattedDateFr(truck.deliveryDate ?? truck.expectedDate)
}



function defineCurrentTruck(truck) {
  currentTruck.value = truck
}

const menuItems = computed(() => [
  {
    label: 'Undocking',
    action: 'unDocking',
    isDisabled: !currentTruck.value?.dock
  },
  {
    label: 'Reset',
    action: 'resetItem',
  },
])

const unDocking = () => dockingTruck(currentTruck.value, null)
const resetItem = () => dockingTruck(currentTruck.value, null, true)


const handleMenuAction = (action) => {
  const actions = { unDocking, resetItem, shareItem }
  if (actions[action]) actions[action]()
}

</script>
