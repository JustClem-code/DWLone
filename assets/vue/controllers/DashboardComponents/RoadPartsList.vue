<template>
  <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700/90">
    <li v-for="roadPart in roadParts" :key=roadPart.id @click="setCurrentRoadPart(roadPart)"
      class="flex items-center justify-between py-4">
      <div>
        <div class="flex items-center gap-4 text-base font-semibold">
          <p>{{ roadPart.road }} #{{ roadPart.number }}</p>
          <BadgeComponent :type="badgeType(roadPart)" :title="badgeTitle(roadPart)" size="sm" />
        </div>
        <p class="text-sm font-light text-gray-800 dark:text-gray-400">{{ roadPart.truckName }}</p>
      </div>
      <div class="flex items-center gap-2">
        <MinimalToggleMenu :items="menuItems" @select="(item) => handleMenuAction(item, actions)" />
      </div>
    </li>
  </ul>

  <DialogComponentSlot ref="infoDialogRef" :hasCloseCross="true">
    <RoadPartInfo :currentRoadPart="currentRoadPart" />
  </DialogComponentSlot>
  <DialogComponentSlot ref="confirmResetDialogRef">
    <ConfirmationComponent question="Are you sure to reset ?" @confirm="resetItem"
      @cancel="confirmResetDialogRef?.closeDialog()" />
  </DialogComponentSlot>
</template>

<script setup>
import { inject, ref, computed } from 'vue'
import { useLogic } from '../../composables/useLogic.js'

import BadgeComponent from '../UI/BadgeComponent.vue';
import MinimalToggleMenu from '../UI/MinimalToggleMenu.vue';
import DialogComponentSlot from '../UI/Modals/DialogComponentSlot.vue';
import ConfirmationComponent from '../UI/Modals/ConfirmationComponent.vue';

import RoadPartInfo from '../DashboardComponents/RoadPartInfo.vue';

const { handleMenuAction } = useLogic()

const { resetRoadpart, globalLoading } = inject('pickingProcessing')

const props = defineProps({
  roadParts: Array,
})

const currentRoadPart = ref(null)

const infoDialogRef = ref(null);

const confirmResetDialogRef = ref(null);

const badgeType = (roadPart) => {
  if (!roadPart.userName) {
    return 'danger'
  }

  return roadPart.stagged ? 'valid' : 'warning'
}

const badgeTitle = (roadPart) => {
  if (!roadPart.userName) {
    return 'waiting'
  }

  return roadPart.stagged ? 'stagged' : 'pending'
}

const setCurrentRoadPart = (roadPart) => {
  currentRoadPart.value = roadPart
}

const menuItems = computed(() => [
  {
    label: 'Reset',
    action: 'confirmResetItem',
    isDisabled:
      globalLoading.value || !currentRoadPart.value?.userName
  },
  {
    label: 'Infos',
    action: 'openInfos',
  },
])

const resetItem = () => {
  resetRoadpart(currentRoadPart.value, true)
  confirmResetDialogRef.value?.closeDialog()
}
const confirmResetItem = () => confirmResetDialogRef.value?.openDialog()
const openInfos = () => {
  infoDialogRef.value?.openDialog()
}

const actions = { confirmResetItem, openInfos }

</script>
