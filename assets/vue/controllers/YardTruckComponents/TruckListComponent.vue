<template>
  <ul role="list" class="divide-y">
    <li v-for="truck in trucks" :key=truck.id
      class="flex items-center justify-between py-4 border-gray-200 dark:border-gray-700/90">
      <div>
        <div class="flex items-center gap-4 text-base font-semibold">
          <p>{{ truck.name }}</p>
          <BadgeComponent :type="badgeType(truck)" :title="badgeTitle(truck)" size="sm" />
        </div>
        <p class="text-sm font-light text-gray-800 dark:text-gray-400">{{ dateInfo(truck) }}</p>
      </div>
      <div class="flex items-center gap-2">
        <SelectDialogComponentSlot v-slot:activator :options="docks" :isLoading="dockingIsLoading"
          @submitOption="val => dockingTruck(truck, val.selected)">
          <BaseButton title="Docks" styleColor="empty" size="sm" :isDisabled="!!truck.departureDate" />
        </SelectDialogComponentSlot>
        <MinimalToggleMenu :items="menuItems" @click="setCurrentTruck(truck)" @select="handleMenuAction" />
      </div>
    </li>
    <InfoDialogComponentSlot ref="infoDialogRef" :hasCloseCross="true">
      <TruckInfo :currentTruck="currentTruck" />
    </InfoDialogComponentSlot>
    <InfoDialogComponentSlot ref="confirmUndockDialogRef">
      <ConfirmationComponent question="Are you sure to undock ?" @confirm="unDocking"
        @cancel="confirmUndockDialogRef?.closeDialog()" />
    </InfoDialogComponentSlot>
    <InfoDialogComponentSlot ref="confirmResetDialogRef">
      <ConfirmationComponent question="Are you sure to reset ?" @confirm="resetItem"
        @cancel="confirmResetDialogRef?.closeDialog()" />
    </InfoDialogComponentSlot>
  </ul>
</template>

<script setup>
import { inject, ref, toRef, computed } from 'vue'
import BadgeComponent from '../UI/BadgeComponent.vue';
import MinimalToggleMenu from '../UI/MinimalToggleMenu.vue';
import SelectDialogComponentSlot from '../UI/SelectDialogComponentSlot.vue';
import BaseButton from '../UI/BaseButton.vue';
import InfoDialogComponentSlot from '../UI/InfoDialogComponentSlot.vue';
import ConfirmationComponent from '../UI/ConfirmationComponent.vue';

import TruckInfo from './TruckInfo.vue';

import { formattedDateFr } from '../../composables/dateFormat.js'

const { trucks, dockingTruck } = inject('yardTruck')

const yardTruck = inject('yardTruck')
const dockingIsLoading = toRef(yardTruck, 'dockingIsLoading')

const props = defineProps({
  trucks: Array,
  docks: Array,
})

const currentTruck = ref(null)

const infoDialogRef = ref(null);

const confirmUndockDialogRef = ref(null);

const confirmResetDialogRef = ref(null);

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

const setCurrentTruck = (truck)  => {
  currentTruck.value = truck
}

const menuItems = computed(() => [
  {
    label: 'Undocking',
    action: 'confirmUndocking',
    isDisabled: !currentTruck.value?.dock
  },
  {
    label: 'Reset',
    action: 'confirmResetItem',
  },
  {
    label: 'Infos',
    action: 'openInfos',
  },
])

const unDocking = () => {
  dockingTruck(currentTruck.value, null)
  confirmUndockDialogRef.value?.closeDialog()
}
const resetItem = () => {
  dockingTruck(currentTruck.value, null, true)
  confirmResetDialogRef.value?.closeDialog()
}
const confirmUndocking = () => confirmUndockDialogRef.value?.openDialog()
const confirmResetItem = () => confirmResetDialogRef.value?.openDialog()
const openInfos = () => infoDialogRef.value?.openDialog()

const handleMenuAction = (action) => {
  const actions = { confirmUndocking, confirmResetItem, openInfos }
  if (actions[action]) actions[action]()
}

</script>
