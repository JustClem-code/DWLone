<template>
  <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700/90">
    <li v-for="pallet in pallets" :key=pallet.id @click="setCurrentPallet(pallet)"
      class="flex items-center justify-between py-4">
      <div>
        <div class="flex items-center gap-4 text-base font-semibold">
          <p>{{ pallet.id }}</p>
          <BadgeComponent :type="badgeType(pallet)" :title="badgeTitle(pallet)" size="sm" />
        </div>
        <p class="text-sm font-light text-gray-800 dark:text-gray-400">INFOOOO</p>
      </div>
      <div class="flex items-center gap-2">

        <BaseButton title="ASML" styleColor="empty" size="sm"
          @click="SelectOptionRef?.openDialog()" />
        <!-- <BaseButton title="Docks" styleColor="empty" size="sm" :isDisabled="!!truck.departureDate"
          @click="SelectOptionRef?.openDialog()" /> -->

        <MinimalToggleMenu :items="menuItems" @select="handleMenuAction" />
      </div>
    </li>
  </ul>
  <DialogComponentSlot ref="SelectOptionRef">
    <!-- <SelectOptionComponent :options="docks" :isLoading="dockingIsLoading"
      @submitOption="val => dockingTruck(currentPallet, val.selected)" @closeDialog="SelectOptionRef?.closeDialog()" /> -->
      <p>Test options</p>
  </DialogComponentSlot>

  <DialogComponentSlot ref="infoDialogRef" :hasCloseCross="true">
    <PalletInfo :currentPallet="currentPallet"/>
  </DialogComponentSlot>
  <DialogComponentSlot ref="confirmUndockDialogRef">
    <!-- <ConfirmationComponent question="Are you sure to undock ?" @confirm="unDocking"
      @cancel="confirmUndockDialogRef?.closeDialog()" /> -->
  </DialogComponentSlot>
  <DialogComponentSlot ref="confirmResetDialogRef">
    <ConfirmationComponent question="Are you sure to reset ?" @confirm="resetItem"
      @cancel="confirmResetDialogRef?.closeDialog()" />
  </DialogComponentSlot>
</template>

<script setup>
import { inject, ref, computed } from 'vue'
import BadgeComponent from '../UI/BadgeComponent.vue';
import MinimalToggleMenu from '../UI/MinimalToggleMenu.vue';
import DialogComponentSlot from '../UI/Modals/DialogComponentSlot.vue';
import BaseButton from '../UI/Buttons/BaseButton.vue';
import SelectOptionComponent from '../UI/Modals/SelectOptionComponent.vue';
import ConfirmationComponent from '../UI/Modals/ConfirmationComponent.vue';

import PalletInfo from './PalletInfo.vue';

const { unloadingPallet, unLoadingIsLoading } = inject('unLoading')
// const { trucks, dockingTruck, dockingIsLoading } = inject('yardTruck')

const props = defineProps({
  pallets: Array,
})

const currentPallet = ref(null)

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

const setCurrentPallet = (pallet) => {
  currentPallet.value = pallet
}

const menuItems = computed(() => [
  {
    label: 'Undocking',
    action: 'confirmUndocking',
    isDisabled: !currentPallet.value?.dock
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
  unloadingPallet(currentPallet.value, null)
  confirmUndockDialogRef.value?.closeDialog()
}
const resetItem = () => {
  unloadingPallet(currentPallet.value, true)
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
