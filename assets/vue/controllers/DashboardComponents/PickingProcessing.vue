<template>
  <div class="flex flex-col gap-8">

    <StatsHeader title="Picking processing" notice="You can see the picking overview" actionTitle="Action on picking"
      @actionClick="sidePanelRef?.toggleSidePanel()" :statistics="pickingStats" />

    <!-- <div v-if="locations" class="grid grid-cols-4 gap-4 md:gap-10">
      <div v-for="(groupe, indexGroup) in locations" :key="indexGroup" class="grid grid-cols-6 gap-1">

        <div v-for="location in groupe" :key="location.id" class="size-1"
          :class="location.bag?.packages?.length > 0 ? getBagColor(location.bag?.name) : 'bg-gray-200 dark:bg-gray-700/90'">
        </div>

      </div>
    </div> -->

    <SidePanel ref="sidePanelRef" title="Action on picking" width="md:w-5/6">

      <!-- <div v-if="locations" class="divide-y divide-gray-200 dark:divide-gray-700/90">
        <div v-for="(groupe, indexGroup) in locations" :key="indexGroup" class="grid grid-cols-6 gap-1 sm:gap-4 py-8">

          <HorizontalLinkButton v-for="location in groupe" :key="location.id" @click="setCurrentBag(location.bag)"
            :title="location.name"
            :focused="location.bag?.packages?.length > 0 ? getBagColorZoom(location.bag?.name) : 'text-gray-200 dark:text-gray-700/90'" />

        </div>
      </div> -->

    </SidePanel>

    <!-- <DialogComponentSlot ref="infoDialogRef" :hasCloseCross="true">
      <InformationComponent :informations="bagInfos" />
    </DialogComponentSlot> -->

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

const pickingStats = computed(() => [
  { 'title': 'Number of bags', 'number': `${numberOfBags.value}` },
  { 'title': 'Number of road', 'number': `0` },
  { 'title': 'Picking progress', 'number': `0` },
])





</script>
