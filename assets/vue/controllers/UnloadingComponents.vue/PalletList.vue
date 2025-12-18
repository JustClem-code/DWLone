<template>
  <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700/90">
    <li v-for="pallet in pallets" :key=pallet.id @click="setCurrentPallet(pallet)"
      class="flex items-center justify-between py-4">
      <div>
        <div class="flex items-center gap-4 text-base font-semibold">
          <p>{{ pallet.id }}</p>
          <BadgeComponent :type="badgeType(pallet)" :title="badgeTitle(pallet)" size="sm" />
        </div>
        <p class="text-sm font-light text-gray-800 dark:text-gray-400">{{ pallet.truckName }}</p>
      </div>
      <div class="flex items-center gap-2">
        <MinimalToggleMenu :items="menuItems" @select="handleMenuAction" />
      </div>
    </li>
  </ul>

  <DialogComponentSlot ref="infoDialogRef" :hasCloseCross="true">
    <PalletInfo :currentPallet="currentPallet" />
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
import ConfirmationComponent from '../UI/Modals/ConfirmationComponent.vue';

import PalletInfo from '../SharedComponents/PalletInfo.vue';

const { unloadingPallet } = inject('unLoading')

const props = defineProps({
  pallets: Array,
})

const currentPallet = ref(null)

const infoDialogRef = ref(null);

const confirmResetDialogRef = ref(null);

const getNumberOfPackagesNotInducted = (pallet) => {
  return pallet?.packages.filter(p => p.location === null).length
}

const badgeType = (pallet) => {
  return getNumberOfPackagesNotInducted(pallet) === 0 ? 'warning' : 'valid';
}

const badgeTitle = (pallet) => {
  return getNumberOfPackagesNotInducted(pallet) === 0 ? 'Empty' : `${getNumberOfPackagesNotInducted(pallet)}`;
}

const setCurrentPallet = (pallet) => {
  currentPallet.value = pallet
}

const menuItems = computed(() => [
  {
    label: 'Reset',
    action: 'confirmResetItem',
    isDisabled:
      getNumberOfPackagesNotInducted(currentPallet?.value) !== currentPallet.value?.packages.length
  },
  {
    label: 'Infos',
    action: 'openInfos',
  },
])

const resetItem = () => {
  unloadingPallet(currentPallet.value, true)
  confirmResetDialogRef.value?.closeDialog()
}
const confirmResetItem = () => confirmResetDialogRef.value?.openDialog()
const openInfos = () => {
  infoDialogRef.value?.openDialog()
}


const handleMenuAction = (action) => {
  const actions = { confirmResetItem, openInfos }
  if (actions[action]) actions[action]()
}

</script>
