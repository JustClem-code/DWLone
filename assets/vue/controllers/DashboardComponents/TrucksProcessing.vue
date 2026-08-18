<template>
  <div class="flex flex-col gap-2">

    <StatsHeader title="Trucks processing" notice="You can automate the steps" actionTitle="Automating steps"
      @actionClick="sidePanelRef?.toggleSidePanel()" :statistics="trucksAndPalletsStats" />

    <SidePanel ref="sidePanelRef" title="Automating steps">

      <div class="flex flex-col gap-2 mb-8">

        <RadioCard v-for="option in automaticOptions" :key="option.value" :option="option" v-model="selected" />

        <BaseButton class="mt-4" @click="submitAutomaticForm" title="Automatic program" styleColor="primary"
          :isDisabled="!selected" :isLoading="globalLoading" />
      </div>
    </SidePanel>

  </div>
</template>

<script setup>
import { ref, computed, inject, watch, watchEffect, onMounted } from 'vue';
import { useFetch, usePostFetch } from '../../composables/fetch.js'
import { useNotification } from '../../composables/eventBus.js'

import BaseButton from '../UI/Buttons/BaseButton.vue';
import RadioCard from '../UI/Radios/RadioCard.vue';
import SidePanel from '../UI/SidePanel.vue';
import StatsHeader from './StatsHeader.vue';

const { data: yardTruckStats, error: errorYardTruckStats } = useFetch('/getyardtruckstats')

const { notifier } = useNotification()

const sidePanelRef = ref(null)

const selected = ref(null)
const globalLoading = ref(null)
const hardResetIsLoading = ref(null)

const expectedTrucksNumber = computed(() => {
  return yardTruckStats.value ? yardTruckStats.value.expectedTrucks : 0
})

const expectedPalletsNumber = computed(() => {
  return yardTruckStats.value ? yardTruckStats.value.expectedPallets : 0
})

const waitingTrucksNumber = computed(() => {
  return yardTruckStats.value ? yardTruckStats.value.waitingTrucks : 0
})

const waitingPalletsNumber = computed(() => {
  return yardTruckStats.value ? yardTruckStats.value.waitingPallets : 0
})

const unloadingPalletsNumber = computed(() => {
  return yardTruckStats.value ? yardTruckStats.value.unloadingPallets : 0
})

const unloadingPercentage = computed(() => {
  if (expectedPalletsNumber.value === 0) return 0
  return Math.round((unloadingPalletsNumber.value / expectedPalletsNumber.value) * 100)
})

const trucksAndPalletsStats = computed(() => [
  { 'title': 'Number of trucks', 'number': `${expectedTrucksNumber.value}` },
  { 'title': 'Number of waiting trucks', 'number': `${waitingTrucksNumber.value}` },
  { 'title': 'Unloading progress', 'number': `${unloadingPercentage.value}%` },
])

const automaticOptions = computed(() => [
  { 'value': 'Docking', 'notice': 'Automating of trucks docking', 'number': `${waitingTrucksNumber.value}`, 'disabled': waitingTrucksNumber.value === 0 },
  { 'value': 'Unloading', 'notice': 'Automating of pallets unloading', 'number': `${waitingPalletsNumber.value}`, 'disabled': waitingPalletsNumber.value === 0 },
  // { 'value': 'Full', 'notice': 'Automating every step', 'number': `${packagesFullAutomatingNumber.value}`, 'disabled': packagesFullAutomatingNumber.value === 0 },
  // { 'value': 'Hard reset', 'notice': 'Reset all steps', 'number': `${packagesToResetNumber.value}`, 'disabled': packagesToResetNumber.value === 0 },
])

function submitAutomaticForm() {
  const actions = {
    'Docking': () => automaticDockingTrucks(),
    'Unloading': () => automaticUnloadingPallets(),
    // 'Full': () => automaticInduct(true, true),
    // 'Hard reset': () => resetLocationsBagsPackages(),
  }

  const run = actions[selected.value]

  if (!run) {
    console.log('error')
  } else {
    run()
  }

  selected.value = null
}

const updateYardTruckStats = (data) => {
  yardTruckStats.value = data.value
}

async function automaticDockingTrucks() {

  globalLoading.value = true

  const { data, error } = await usePostFetch('/automaticdockingtrucks')

  if (error.value) {
    notifier('error', 'Automatic docking trucks', 'An error occurred')
    return
  }

  if (data.value) {
    updateYardTruckStats(data)
    notifier('success', 'Automatic docking trucks', 'all trucks are docked!!!')
    globalLoading.value = false
    sidePanelRef.value?.toggleSidePanel()
  }
}

async function automaticUnloadingPallets() {

  globalLoading.value = true

  const { data, error } = await usePostFetch('/automaticunloadingpallets')

  if (error.value) {
    notifier('error', 'Automatic unloading pallets', 'An error occurred')
    return
  }

  if (data.value) {
    updateYardTruckStats(data)
    notifier('success', 'Automatic unloading pallets', 'all pallets are unloaded!!!')
    globalLoading.value = false
    sidePanelRef.value?.toggleSidePanel()
  }
}

/* async function resetLocationsBagsPackages() {
  hardResetIsLoading.value = true;
  const { data, error } = await usePostFetch('/hardResetLocationsBagsPackages');

  if (data.value) {
    resetLocalStorage()
    hardResetIsLoading.value = false;
    notifier('success', 'Hard reset', `The reset is finished`)

    updatePackagesData(data)
    sidePanelRef.value?.toggleSidePanel()
  }
} */

const handleToggle = () => {

  if (!sidePanelRef.value?.isOpen) {
    selected.value = null
  }
}

watchEffect(handleToggle)

</script>
