<template>
  <div class="flex flex-col gap-2">
    <BaseButton title="Zoom on bags" @click="sidePanelRef?.toggleSidePanel()" styleColor="empty" />

    <SidePanel ref="sidePanelRef" title="Zoom on bags" width="md:w-5/6">

      <div class="grid grid-cols-6 gap-4">
        <HorizontalLinkButton v-for="location in orderedLocations" :key="location.id" @click="setCurrentBag(location.bag)"
          :title="location.name" :focused="location.bag?.packages?.length > 0 ? getBagColor(location.bag?.name) : ''" />
      </div>

    </SidePanel>

    <DialogComponentSlot ref="infoDialogRef" :hasCloseCross="true" :darkerBackground="true">
      <InformationComponent :informations="bagInfos" />
    </DialogComponentSlot>

  </div>
</template>

<script setup>
import { ref, computed, inject, onMounted } from 'vue';
import { useLogic } from '../../composables/useLogic.js'

import BaseButton from '../UI/Buttons/BaseButton.vue';
import SidePanel from '../UI/SidePanel.vue';
import HorizontalLinkButton from '../UI/Buttons/HorizontalLinkButton.vue';
import DialogComponentSlot from '../UI/Modals/DialogComponentSlot.vue';
import InformationComponent from '../UI/Modals/InformationComponent.vue';

const { formatInt } = useLogic()

const { orderedLocations } = inject('dashboard')

onMounted(() => {
  console.log('orderlocations', orderedLocations);

})

const currentBag = ref(null)
const infoDialogRef = ref(null)

const sidePanelRef = ref(null)

const setCurrentBag = (bag) => {
  if (!bag) {
    return
  }
  currentBag.value = bag;
  infoDialogRef.value?.openDialog()
}

const bagInfos = computed(() => {
  return {
    title: "Bag informations",
    datas: [
      { 'Bag': currentBag.value?.name },
      { 'Location': currentBag.value?.locationName },
      { 'Number of packages': currentBag.value?.packages.length },
      { 'Number of packages in bag': currentBag.value?.packages.filter(p => p.userStow !== null).length },
      { 'Total Weight': `${formatInt(currentBag.value?.totalBagWeight)} kg` },
    ]
  }
})

const getBagColor = (name) => {
  const prefix = name.match(/^[^-]+/)[0];
  const colors = {
    'BLK': 'outline-2 outline-offset-2',
    'NVY': 'outline-2 outline-blue-700 outline-offset-2',
    'ORG': 'outline-2 outline-orange-700 outline-offset-2',
    'YLO': 'outline-2 outline-yellow-700 outline-offset-2',
    'GRN': 'outline-2 outline-green-700 outline-offset-2',
  }
  return colors[prefix] ?? '';
}

</script>
