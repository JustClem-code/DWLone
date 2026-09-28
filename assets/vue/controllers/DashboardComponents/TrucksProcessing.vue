<template>
  <div class="flex flex-col gap-2">

    <StatsHeader title="Trucks processing" notice="You can automate the steps" actionTitle="Automating steps"
      @actionClick="sidePanelRef?.toggleSidePanel()" :statistics="trucksAndPalletsStats" />

    <SidePanel ref="sidePanelRef" title="Automating steps">

      <div class="flex flex-col gap-2 mb-8">

        <RadioForm :options="automaticOptions" v-model="selected" :isLoading="globalLoading"
          @submitForm="submitAutomaticForm" :sidePanelRef="sidePanelRef" />

      </div>
    </SidePanel>

  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { usePostFetch } from '../../composables/fetch.js'
import { useNotification } from '../../composables/eventBus.js'
import { dashboardStore } from '../../composables/dashboardStore.js'

import SidePanel from '../UI/SidePanel.vue';
import StatsHeader from './StatsHeader.vue';
import RadioForm from '../UI/RadioForm.vue';

const { yardTruckStats, updateDashboardData } = dashboardStore()

const { notifier } = useNotification()

const sidePanelRef = ref(null)

const selected = ref(null)
const globalLoading = ref(null)

const expectedTrucksNumber = computed(() => {
  return yardTruckStats.value ? yardTruckStats.value.expectedTrucks : 0
})

const expectedPalletsNumber = computed(() => {
  return yardTruckStats.value ? yardTruckStats.value.expectedPallets : 0
})

const waitingTrucksNumber = computed(() => {
  return yardTruckStats.value ? yardTruckStats.value.waitingTrucks : 0
})

const processedTrucksNumber = computed(() => {
  return yardTruckStats.value ? yardTruckStats.value.processedTrucks : 0
})

const waitingPalletsNumber = computed(() => {
  return yardTruckStats.value ? yardTruckStats.value.waitingPallets : 0
})

const waitingPalletsDockedNumber = computed(() => {
  return yardTruckStats.value ? yardTruckStats.value.waitingPalletsDocked : 0
})

const unloadingPalletsNumber = computed(() => {
  return yardTruckStats.value ? yardTruckStats.value.unloadingPallets : 0
})

const unloadingPalletsCleanNumber = computed(() => {
  return yardTruckStats.value ? yardTruckStats.value.unloadingPalletsClean : 0
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
  {
    'value': 'Docking',
    'notice': 'Automating of trucks docking',
    'number': `${waitingTrucksNumber.value}`,
    'disabled': waitingTrucksNumber.value === 0
  },
  {
    'value': 'Unloading',
    'notice': 'Automating of pallets unloading',
    'number': `${waitingPalletsDockedNumber.value}`,
    'disabled': waitingPalletsDockedNumber.value === 0
  },
  {
    'value': 'Full',
    'notice': 'Automating every step',
    'number': `${waitingTrucksNumber.value} & ${waitingPalletsNumber.value}`,
    'disabled': waitingTrucksNumber.value === 0 || waitingPalletsNumber.value === 0
  },
  {
    'value': 'Hard reset',
    'notice': 'Reset all steps',
    'number': `${processedTrucksNumber.value} - ${unloadingPalletsCleanNumber.value}`,
    'disabled': unloadingPalletsCleanNumber.value === 0 || processedTrucksNumber.value === 0
  },
])

const submitAutomaticForm = () => {
  const actions = {
    'Docking': () => automaticDockingTrucks(),
    'Unloading': () => automaticUnloadingPallets(),
    'Full': () => autoDockingAndUnloading(),
    'Hard reset': () => resetDockingAndUnloading(),
  }

  const run = actions[selected.value]

  if (!run) {
    console.log('error');
    return;
  }

  run();
}

const automaticDockingTrucks = async () => {

  globalLoading.value = true

  const { data, error } = await usePostFetch('/automaticdockingtrucks')

  if (error.value) {
    notifier('error', 'Automatic docking trucks', 'An error occurred')
    return
  }

  if (data.value) {
    updateDashboardData(data)
    notifier('success', 'Automatic docking trucks', 'all trucks are docked!!!')
    globalLoading.value = false
    sidePanelRef.value?.toggleSidePanel()
  }
}

const automaticUnloadingPallets = async () => {

  globalLoading.value = true

  const { data, error } = await usePostFetch('/automaticunloadingpallets')

  if (error.value) {
    notifier('error', 'Automatic unloading pallets', 'An error occurred')
    return
  }

  if (data.value) {
    updateDashboardData(data)
    notifier('success', 'Automatic unloading pallets', 'all pallets are unloaded!!!')
    globalLoading.value = false
    sidePanelRef.value?.toggleSidePanel()
  }
}

const autoDockingAndUnloading = async () => {

  globalLoading.value = true

  const { data, error } = await usePostFetch('/autodockingandunloading')

  if (error.value) {
    notifier('error', 'Automatic docking and unloading', 'An error occurred')
    return
  }

  if (data.value) {
    updateDashboardData(data)
    notifier('success', 'Automatic docking and unloading', 'Docking and unloading are finished!!!')
    globalLoading.value = false
    sidePanelRef.value?.toggleSidePanel()
  }
}

const resetDockingAndUnloading = async () => {

  globalLoading.value = true

  const { data, error } = await usePostFetch('/resetdockingandunloading');

  if (error.value) {
    notifier('error', 'Hard reset', 'An error occurred')
    return
  }

  if (data.value) {
    updateDashboardData(data)
    notifier('success', 'Hard reset', `The reset is finished`)
    globalLoading.value = false
    sidePanelRef.value?.toggleSidePanel()
  }
}

</script>
