<template>
  <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700/90">
    <li v-for="truck in trucks" :key=truck.id @click="setCurrentTruck(truck)"
      class="flex items-center justify-between py-4">
      <div>
        <div class="flex items-center gap-4 text-base font-semibold">
          <p>{{ truck.name }}</p>
          <BadgeComponent :type="badgeType(truck)" :title="badgeTitle(truck)" size="sm" />
        </div>
        <p class="text-sm font-light text-gray-800 dark:text-gray-400">{{ dateInfo(truck) }}</p>
      </div>
      <div class="flex items-center gap-2">

        <BaseButton title="Docks" styleColor="empty" size="sm" :isDisabled="!!truck.departureDate"
          @click="SelectOptionRef?.openDialog()" />

        <MinimalToggleMenu :items="menuItems" @select="handleMenuAction" />
      </div>
    </li>
  </ul>
  <DialogComponentSlot ref="SelectOptionRef">
    <SelectOptionComponent :options="docks" :isLoading="dockingIsLoading"
      @submitOption="val => dockingTruck(currentTruck, val.selected)" @closeDialog="SelectOptionRef?.closeDialog()" />
  </DialogComponentSlot>

  <DialogComponentSlot ref="infoDialogRef" :hasCloseCross="true">
    <TruckInfo :currentTruck="currentTruck" />
  </DialogComponentSlot>
  <DialogComponentSlot ref="confirmUndockDialogRef">
    <ConfirmationComponent question="Are you sure to undock ?" @confirm="unDocking"
      @cancel="confirmUndockDialogRef?.closeDialog()" />
  </DialogComponentSlot>
  <DialogComponentSlot ref="confirmResetDialogRef">
    <ConfirmationComponent question="Are you sure to reset ?" @confirm="resetItem"
      @cancel="confirmResetDialogRef?.closeDialog()" />
  </DialogComponentSlot>
</template>

<script setup>
import { inject, ref, toRef, computed } from 'vue'
import BadgeComponent from '../UI/BadgeComponent.vue';
import MinimalToggleMenu from '../UI/MinimalToggleMenu.vue';
import DialogComponentSlot from '../UI/Modals/DialogComponentSlot.vue';
import BaseButton from '../UI/Buttons/BaseButton.vue';
import SelectOptionComponent from '../UI/Modals/SelectOptionComponent.vue';
import ConfirmationComponent from '../UI/Modals/ConfirmationComponent.vue';

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

const SelectOptionRef = ref(null);

const infoDialogRef = ref(null);

const confirmUndockDialogRef = ref(null);

const confirmResetDialogRef = ref(null);

const badgeType = (truck) => {
  if (truck.departureDate) {
    return 'danger'
  }
  return truck.dock ? 'warning' : 'valid';
}

const badgeTitle = (truck) => {
  if (truck.departureDate) {
    return 'finish'
  }
  return truck.dock ?? 'Waiting dock'
}

const dateInfo = (truck) => {
  if (truck.departureDate) {
    return formattedDateFr(truck.departureDate)
  }
  return formattedDateFr(truck.deliveryDate ?? truck.expectedDate)
}

const setCurrentTruck = (truck) => {
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
