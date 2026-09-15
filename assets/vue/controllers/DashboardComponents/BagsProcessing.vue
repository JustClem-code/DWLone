<template>
  <div class="flex flex-col gap-8">

    <StatsHeader title="Bags processing" notice="You can see bags overview" actionTitle="Zoom on bags"
      @actionClick="sidePanelRef?.toggleSidePanel()" :statistics="bagsStats" />

    <div v-if="locations" class="grid grid-cols-4 gap-4 md:gap-10">
      <div v-for="(groupe, indexGroup) in locations" :key="indexGroup" class="grid grid-cols-6 gap-1">

        <div v-for="location in groupe" :key="location.id" class="size-1"
          :class="location.bag?.packages?.length > 0 ? bagColorClass(location.bag?.name) : 'bg-gray-200 dark:bg-gray-700/90'">
        </div>
      </div>
    </div>

    <SidePanel ref="sidePanelRef" title="Zoom on bags" width="md:w-5/6">

      <SearchComponent :items="filteredBagsItems" v-model="searchQuery" @click="val => setCurrentBag(val)">
      </SearchComponent>

      <div v-if="locations" class="divide-y divide-gray-200 dark:divide-gray-700/90">
        <div v-for="(groupe, indexGroup) in locations" :key="indexGroup" class="grid grid-cols-6 gap-1 sm:gap-4 py-8">

          <HorizontalLinkButton v-for="location in groupe" :key="location.id" @click="setCurrentBag(location.bag)"
            :title="location.name"
            :focused="location.bag?.packages?.length > 0 ? bagColorClass(location.bag?.name, true) : 'text-gray-200 dark:text-gray-700/90'" />

        </div>
      </div>

    </SidePanel>

    <DialogComponentSlot ref="infoDialogRef" :hasCloseCross="true">
      <InformationComponent :informations="bagInfos" />
    </DialogComponentSlot>

  </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import { useLogic } from '../../composables/useLogic.js'
import { dashboardStore } from '../../composables/dashboardStore.js'

import SidePanel from '../UI/SidePanel.vue';
import HorizontalLinkButton from '../UI/Buttons/HorizontalLinkButton.vue';
import DialogComponentSlot from '../UI/Modals/DialogComponentSlot.vue';
import InformationComponent from '../UI/Modals/InformationComponent.vue';
import StatsHeader from './StatsHeader.vue';
import SearchComponent from '../UI/Modals/SearchComponent.vue';

const { formatInt, getColor } = useLogic()

const { locations } = dashboardStore()

const currentBag = ref(null)
const infoDialogRef = ref(null)

const sidePanelRef = ref(null)

const searchQuery = ref('')

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

const allBagsItems = computed(() => {
  return locations.value
    ? locations.value
      .flatMap(group =>
        group
          .filter(loc => loc.bag !== null)
          .map(loc => ({
            label: loc.bag.name,
            color: bagColorSearchClass.value(loc.bag.name),
            ...loc.bag
          }))
      )
    : []
})

const filteredBagsItems = computed(() => {
  const query = searchQuery.value.trim().toLowerCase()

  if (!query) {
    return allBagsItems.value
  }

  return allBagsItems.value.filter(bag => {
    return (
      bag.label?.toLowerCase().includes(query) ||
      bag.name?.toLowerCase().includes(query)
    )
  })
})

const allPackagesItems = computed(() => {
  return allBagsItems.value.flatMap(bag =>
    (bag.packages || []).map(pkg => ({
      label: pkg.id,
      ...pkg
    }))
  )
})

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
      { 'Road': currentBag.value?.road ? currentBag.value?.road : 'No road assigned' },
      { 'Number of packages': currentBag.value?.packages.length },
      { 'Number of packages in bag': currentBag.value?.packages.filter(p => p.userStow !== null).length },
      { 'Total Weight': `${formatInt(currentBag.value?.totalBagWeight)} kg` },
      { 'Picked': currentBag.value?.isPicked ? 'Yes' : 'No' },
      { 'Stagged': currentBag.value?.isStagged ? 'Yes' : 'No' },
      { 'Stagging Area': currentBag.value?.staggingArea ? currentBag.value?.staggingArea : 'No stagging area assigned' },
    ]
  }
})

const bagColorMap = computed(() => ({
  BLK: '',
  NVY: 'outline-blue-700 bg-blue-700',
  ORG: 'outline-orange-700 bg-orange-700',
  YLO: 'outline-yellow-700 bg-yellow-700',
  GRN: 'outline-green-700 bg-green-700',
}))

const bagColorZoomMap = computed(() => ({
  BLK: '',
  NVY: 'outline-blue-700',
  ORG: 'outline-orange-700',
  YLO: 'outline-yellow-700',
  GRN: 'outline-green-700',
}))

const bagColorSearchMap = computed(() => ({
  BLK: 'dark:bg-gray-900 bg-gray-900/50',
  NVY: 'bg-blue-700',
  ORG: 'bg-orange-700',
  YLO: 'bg-yellow-700',
  GRN: 'bg-green-700',
}))

const bagColorSearchClass = computed(() => {
  return (name) => {
    const prefix = getColor(name)
    return `${bagColorSearchMap.value[prefix] || null}`.trim();
  }
})

const bagColorClass = computed(() => {
  return (name, zoom = false) => {
    const prefix = getColor(name)
    return zoom ?
      `outline sm:outline-2  outline-offset sm:outline-offset-2 ${bagColorZoomMap.value[prefix] || ''}`.trim() :
      `outline outline-offset-1 ${bagColorMap.value[prefix] || ''}`.trim()
  }
})

watch(
  () => searchQuery.value,
  (val) => {
    console.log('searchQuery watch:', val)
  },
  { immediate: true, deep: true }
)

</script>
