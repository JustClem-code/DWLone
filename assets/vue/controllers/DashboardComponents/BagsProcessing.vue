<template>
  <div class="flex flex-col gap-8">

    <StatsHeader title="Bags processing" notice="You can automate the steps" actionTitle="Zoom on bags"
      @actionClick="sidePanelRef?.toggleSidePanel()" :statistics="bagsStats" />

    <div v-if="locations" class="grid grid-cols-4 gap-4 md:gap-10">
      <div v-for="(groupe, indexGroup) in locations" :key="indexGroup" class="grid grid-cols-6 gap-1">

        <div v-for="location in groupe" :key="location.id" class="size-1"
          :class="location.bag?.packages?.length > 0 ? getBagColor(location.bag?.name) : 'bg-gray-200 dark:bg-gray-700/90'">
        </div>

      </div>
    </div>

    <SidePanel ref="sidePanelRef" title="Zoom on bags" width="md:w-5/6">

      <div v-if="locations" class="divide-y divide-gray-200 dark:divide-gray-700/90">
        <div v-for="(groupe, indexGroup) in locations" :key="indexGroup" class="grid grid-cols-6 gap-1 sm:gap-4 py-8">

          <HorizontalLinkButton v-for="location in groupe" :key="location.id" @click="setCurrentBag(location.bag)"
            :title="location.name"
            :focused="location.bag?.packages?.length > 0 ? getBagColorZoom(location.bag?.name) : 'text-gray-200 dark:text-gray-700/90'" />

        </div>
      </div>

    </SidePanel>

    <DialogComponentSlot ref="infoDialogRef" :hasCloseCross="true">
      <InformationComponent :informations="bagInfos" />
    </DialogComponentSlot>

  </div>
</template>

<script setup>
import { ref, computed, inject } from 'vue';
import { useLogic } from '../../composables/useLogic.js'

import SidePanel from '../UI/SidePanel.vue';
import HorizontalLinkButton from '../UI/Buttons/HorizontalLinkButton.vue';
import DialogComponentSlot from '../UI/Modals/DialogComponentSlot.vue';
import InformationComponent from '../UI/Modals/InformationComponent.vue';
import StatsHeader from './StatsHeader.vue';

const { formatInt, getColor } = useLogic()

const { locations } = inject('dashboard')

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

const numberOfBags = computed(() =>
  locations.value
    ? locations.value
      .flatMap(group => group.filter(loc => loc.bag !== null))
      .length
    : 0
)

const bagsStats = computed(() => [
  { 'title': 'Number of bags', 'number': `${numberOfBags.value}` },
  { 'title': 'Number of road', 'number': `0` },
  { 'title': 'Picking progress', 'number': `0` },
])

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

const getBagColorZoom = (name) => {
  const prefix = getColor(name);
  const colors = {
    'BLK': '',
    'NVY': 'outline-blue-700',
    'ORG': 'outline-orange-700',
    'YLO': 'outline-yellow-700',
    'GRN': 'outline-green-700',
  }
  return `outline sm:outline-2  outline-offset sm:outline-offset-2 ${colors[prefix]}` ?? '';
}


const getBagColor = (name) => {
  const prefix = getColor(name);
  const colors = {
    'BLK': '',
    'NVY': 'outline-blue-700 bg-blue-700',
    'ORG': 'outline-orange-700 bg-orange-700',
    'YLO': 'outline-yellow-700 bg-yellow-700',
    'GRN': 'outline-green-700 bg-green-700',
  }
  return `outline outline-offset-1 ${colors[prefix]}` ?? '';
}

</script>
