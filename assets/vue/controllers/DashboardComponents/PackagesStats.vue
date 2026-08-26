<template>
  <div class="flex flex-col gap-2">

    <StatsHeader title="Packages statistics" notice="You can automate the steps" actionTitle="Automating steps"
      @actionClick="sidePanelRef?.toggleSidePanel()" :statistics="packagesStats" />

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
import { ref, computed, watchEffect, onMounted } from 'vue';
import { usePostFetch } from '../../composables/fetch.js'
import { useNotification } from '../../composables/eventBus.js'
import { dashboardStore } from '../../composables/dashboardStore.js'

import BaseButton from '../UI/Buttons/BaseButton.vue';
import RadioCard from '../UI/Radios/RadioCard.vue';
import SidePanel from '../UI/SidePanel.vue';
import StatsHeader from './StatsHeader.vue';

const { locations, allPackagesStats, updateDashboardData } = dashboardStore()

const { notifier } = useNotification()

onMounted(() => {
  console.log('locations', locations)
})

const STORAGE_KEY_PALLET = 'currentPallet'

const sidePanelRef = ref(null)

const selected = ref(null)
const globalLoading = ref(null)

const allPackagesNumber = computed(() => {
  return allPackagesStats.value ? allPackagesStats.value.allPackagesNumber : 0
})

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
)

const packagesStats = computed(() => [
  { 'title': 'Number of packages', 'number': `${allPackagesNumber.value}` },
  { 'title': 'Induct progress', 'number': `${inductPercentage.value}%` },
  { 'title': 'Stow progress', 'number': `${stowPercentage.value}%` },
])

const automaticOptions = computed(() => [
  { 'value': 'Induct', 'notice': 'Automating of pallet induct on floor', 'number': `${packagesWithoutLocationNumber.value}`, 'disabled': packagesWithoutLocationNumber.value === 0 },
  { 'value': 'Stow', 'notice': 'Automating of packages stow', 'number': `${packagesWithLocationNotStowedNumber.value}`, 'disabled': packagesWithLocationNotStowedNumber.value === 0 },
  { 'value': 'Full', 'notice': 'Automating every step', 'number': `${packagesFullAutomatingNumber.value}`, 'disabled': packagesFullAutomatingNumber.value === 0 },
  { 'value': 'Hard reset', 'notice': 'Reset all steps', 'number': `${packagesToResetNumber.value}`, 'disabled': packagesToResetNumber.value === 0 },
])

function submitAutomaticForm() {
  const actions = {
    'Induct': () => automaticInduction(),
    'Stow': () => automaticStow(),
    'Full': () => autoInductionAndStow(),
    'Hard reset': () => resetLocationsBagsPackages(),
  }

  const run = actions[selected.value]

  if (!run) {
    console.log('error')
  } else {
    run()
  }

  selected.value = null
}

const resetLocalStorage = () => {
  localStorage.removeItem(STORAGE_KEY_PALLET)
}

async function automaticInduction() {

  globalLoading.value = true

  const { data, error } = await usePostFetch('/automaticinduction')

  if (error.value) {
    notifier('error', 'Automatic induction', 'An error occurred')
    return
  }

  if (data.value) {
    updateDashboardData(data)
    notifier('success', 'Automatic induction', 'Induction is finished!!!')
    globalLoading.value = false
    sidePanelRef.value?.toggleSidePanel()
  }
}

async function automaticStow() {

  globalLoading.value = true

  const { data, error } = await usePostFetch('/automaticstow')

  if (error.value) {
    notifier('error', 'Automatic stow', 'An error occurred')
    return
  }

  if (data.value) {
    updateDashboardData(data)
    notifier('success', 'Automatic stow', 'Stow is finished!!!')
    globalLoading.value = false
    sidePanelRef.value?.toggleSidePanel()
  }
}

async function autoInductionAndStow() {

  globalLoading.value = true

  const { data, error } = await usePostFetch('/autoinductionandstow')

  if (error.value) {
    notifier('error', 'Automatic induct and stow', 'An error occurred')
    return
  }

  if (data.value) {
    updateDashboardData(data)
    notifier('success', 'Automatic induct and stow', 'Induct and stow are finished!!!')
    globalLoading.value = false
    sidePanelRef.value?.toggleSidePanel()
  }
}

async function resetLocationsBagsPackages() {
  globalLoading.value = true;
  const { data, error } = await usePostFetch('/hardResetLocationsBagsPackages');

  if (data.value) {
    resetLocalStorage()
    globalLoading.value = false;
    notifier('success', 'Hard reset', `The reset is finished`)

    updateDashboardData(data)
    sidePanelRef.value?.toggleSidePanel()
  }
}

const handleToggle = () => {

  if (!sidePanelRef.value?.isOpen) {
    selected.value = null
  }
}

watchEffect(handleToggle)

</script>
