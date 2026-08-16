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

const { data: allTrucks, error: errorAllTrucks } = useFetch('/getexpectedtrucks')
// const { data: allPackagesStats, error: errorPackagesStats } = useFetch('/getPackagesStats')

const { notifier } = useNotification()

const sidePanelRef = ref(null)

const selected = ref(null)
const globalLoading = ref(null)
const hardResetIsLoading = ref(null)

const allTrucksNumber = computed(() => {
  return allTrucks.value ? allTrucks.value.length : 0
})

/*
const packagesWithoutLocationNumber = computed(() => {
  return allPackagesStats.value ? allPackagesStats.value.packagesWithoutLocationNumber : 0
})

const packagesWithLocationNotStowedNumber = computed(() => {
  return allPackagesStats.value ? allPackagesStats.value.packagesWithLocationNotStowedNumber : 0
})

const packagesWithLocationNumber = computed(() => {
  return allPackagesStats.value ? allPackagesStats.value.packagesWithLocationNumber : 0
})

const packagesWithLocationAndStowedNumber = computed(() => {
  return allPackagesStats.value ? allPackagesStats.value.packagesWithLocationAndStowedNumber : 0
})

const packagesToResetNumber = computed(() => {
  return allPackagesStats.value ? packagesWithLocationNumber.value : 0
})

const packagesFullAutomatingNumber = computed(() => {
  return allPackagesStats.value ?
    (packagesWithLocationNotStowedNumber.value >= packagesWithoutLocationNumber.value ?
      packagesWithLocationNotStowedNumber.value : packagesWithoutLocationNumber.value
    ) : 0
})

const inductPercentage = computed(() => {
  if (allPackagesNumber.value === 0) return 0
  return Math.round((packagesWithLocationNumber.value / allPackagesNumber.value) * 100)
})

const stowPercentage = computed(() =>
  !allPackagesNumber.value || !inductPercentage.value
    ? 0
    : Math.round((packagesWithLocationAndStowedNumber.value / packagesWithLocationNumber.value) * 100)
) */

const trucksAndPalletsStats = computed(() => [
  { 'title': 'Number of trucks', 'number': `${allTrucksNumber.value}` },
  { 'title': 'Induct progress', 'number': `10%` },
  { 'title': 'Stow progress', 'number': `10%` },
])

const automaticOptions = computed(() => [
  { 'value': 'Docking', 'notice': 'Automating of trucks docking', 'number': `0`, 'disabled': false },
  // { 'value': 'Docking', 'notice': 'Automating of trucks docking', 'number': `${packagesWithoutLocationNumber.value}`, 'disabled': packagesWithoutLocationNumber.value === 0 },
  // { 'value': 'Unloading', 'notice': 'Automating of pallets unloading', 'number': `${packagesWithLocationNotStowedNumber.value}`, 'disabled': packagesWithLocationNotStowedNumber.value === 0 },
  // { 'value': 'Full', 'notice': 'Automating every step', 'number': `${packagesFullAutomatingNumber.value}`, 'disabled': packagesFullAutomatingNumber.value === 0 },
  // { 'value': 'Hard reset', 'notice': 'Reset all steps', 'number': `${packagesToResetNumber.value}`, 'disabled': packagesToResetNumber.value === 0 },
])

function submitAutomaticForm() {
  const actions = {
    'Docking': () => automaticDockingTrucks(),
    // 'Stow': () => automaticInduct(false, true),
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

/* const updatePackagesData = (data) => {
  locations.value = data.value.locations
  allPackagesStats.value = data.value.allPackagesStats
}  */

async function automaticDockingTrucks() {

  globalLoading.value = true

  const { data, error } = await usePostFetch('/automaticdockingtrucks')

  if (error.value) {
    notifier('error', 'Automatic docking trucks', 'An error occurred')
    return
  }

  if (data.value) {
    notifier('success', 'Automatic docking trucks', 'all trucks are docked!!!')
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
