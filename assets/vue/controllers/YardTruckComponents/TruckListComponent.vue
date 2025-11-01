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
    <InfoDialogComponentSlot ref="infoDialogRef">

      <h3 class="pb-6 border-b border-gray-200 dark:border-gray-700/90">Truck informations</h3>
      <ul v-if="currentTruck">
        <li v-for="date in currentTruckDates" class="relative flex gap-1 items-center mb-6 last:mb-0">
          <div class="absolute top-0 left-0 -bottom-6 flex w-6 justify-center last:top-4">
            <div class="w-[1px] bg-gray-700/90 h-full"></div>
          </div>
          <div class="relative flex size-6 flex-none items-center justify-center bg-gray-800">
            <div class="size-2 rounded-full bg-gray-700/50 inset-ring inset-ring-gray-700/90"></div>
          </div>
          <p class="text-xs py-1">{{ date.title }}<span class="text-gray-800 dark:text-gray-400">&nbsp;{{date.date}}</span></p>
        </li>
      </ul>

      <p>{{ currentTruck?.name }}</p>
      <p>{{ currentTruck?.dock }}</p>
      <p>{{ formattedDateFr(currentTruck?.expectedDate) ?? 'No date' }}</p>
      <p>{{ formattedDateFr(currentTruck?.deliveryDate) ?? 'No date' }}</p>
      <p>{{ formattedDateFr(currentTruck?.departureDate) ?? 'No date' }}</p>
      <p>{{ currentTruck?.pallets.length }}</p>
    </InfoDialogComponentSlot>
  </ul>
</template>

<script setup>
import { inject, ref, toRef, computed } from 'vue'
import BadgeComponent from '../UI/BadgeComponent.vue';
import MinimalToggleMenu from '../UI/MinimalToggleMenu.vue';
import SelectDialogComponentSlot from '../UI/SelectDialogComponentSlot.vue';
import BaseButton from '../UI/BaseButton.vue';

import { formattedDateFr } from '../../composables/dateFormat.js'
import InfoDialogComponentSlot from '../UI/InfoDialogComponentSlot.vue';

const { trucks, dockingTruck } = inject('yardTruck')

const yardTruck = inject('yardTruck')
const dockingIsLoading = toRef(yardTruck, 'dockingIsLoading')

const props = defineProps({
  trucks: Array,
  docks: Array,
})

const currentTruck = ref(null)

const currentTruckDates = computed(() => [
  { title: 'Expected on', date: formattedDateFr(currentTruck?.value.expectedDate) ?? 'No date' },
  { title: 'Delivered on', date: formattedDateFr(currentTruck?.value.deliveryDate) ?? 'No date' },
  { title: 'Departure on', date: formattedDateFr(currentTruck?.value.departureDate) ?? 'No date' },
])

const infoDialogRef = ref(null);

function badgeType(truck) {
  if (truck.departureDate) {
    return 'danger'
  }
  return truck.dock ? 'warning' : 'valid';
}

function badgeTitle(truck) {
  if (truck.departureDate) {
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
  console.log(currentTruck.value);

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
  {
    label: 'Infos',
    action: 'openInfos',
  },
])

const unDocking = () => dockingTruck(currentTruck.value, null)
const resetItem = () => dockingTruck(currentTruck.value, null, true)
const openInfos = () => infoDialogRef.value?.openDialog()

const handleMenuAction = (action) => {
  const actions = { unDocking, resetItem, openInfos }
  if (actions[action]) actions[action]()
}

</script>
