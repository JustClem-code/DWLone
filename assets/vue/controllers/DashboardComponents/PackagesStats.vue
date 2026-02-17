<template>
  <div class="flex flex-col gap-2">

    <div
      class="w-full bg-white dark:bg-gray-800 border border-0 dark:border-1 rounded-md shadow-sm dark:shadow-none dark:border-gray-700/90">

      <div class="flex items-center justify-between py-6 px-8 border-b border-gray-200 dark:border-gray-700/90">
        <div>
          <h2>Packages statistics</h2>
          <p class="text-xs text-gray-400 mt-2">
            You can automate the steps
          </p>
        </div>
        <div class="flex items-center gap-2">
          <BaseButton title="Automating steps" @click="sidePanelRef?.toggleSidePanel()" styleColor="empty" />
        </div>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-3 max-sm:divide-y sm:divide-x divide-gray-200 dark:divide-gray-700/90">
        <div v-for="stat in packagesStats" :key="stat.title" class="py-6 px-8">
          <p class="text-sm text-gray-400">{{ stat.title }}</p>
          <p class="text-4xl pt-2">{{ stat.number }}</p>
        </div>
      </div>
    </div>


    <SidePanel ref="sidePanelRef" title="Automating steps">

      <div class="flex flex-col gap-2 mb-8">

        <RadioCard v-for="option in automaticOptions" :key="option.value" :option="option" v-model="selected" />

        <BaseButton class="mt-4" @click="submitAutomaticForm" title="Automatic program" styleColor="primary"
          :isDisabled="!selected" :isLoading="hardResetIsLoading || automaticInductIsLoading" />
      </div>
    </SidePanel>

  </div>
</template>

<script setup>
import { ref, computed, inject, watchEffect, onMounted } from 'vue';
import { useFetch, usePostFetch } from '../../composables/fetch.js'
import { useNotification } from '../../composables/eventBus.js'

import BaseButton from '../UI/Buttons/BaseButton.vue';
import RadioCard from '../UI/Radios/RadioCard.vue';
import SidePanel from '../UI/SidePanel.vue';

const { data: allPackagesOnfloor, error: errorAllPackages } = useFetch('/getAllPackagesOnFloor')

const { notifier } = useNotification()

const { locations } = inject('dashboard')

onMounted(() => {
  console.log('locations', locations);
  
})

const STORAGE_KEY_PALLET = 'currentPallet'
const STORAGE_KEY_PAIR = 'currentPair'

const sidePanelRef = ref(null)

const selected = ref(null)
const automaticInductIsLoading = ref(null)
const hardResetIsLoading = ref(null)

const allPackagesNumber = computed(() => {
  return allPackagesOnfloor.value ? allPackagesOnfloor.value.allPackages.length : 0
})

const packagesWithoutLocationNumber = computed(() => {
  return allPackagesOnfloor.value ? allPackagesOnfloor.value.packagesWithoutLocation.length : 0
})

const packagesWithLocationNotStowedNumber = computed(() => {
  return allPackagesOnfloor.value ? allPackagesOnfloor.value.packagesWithLocationNotStowed.length : 0
})

const packagesToResetNumber = computed(() => {
  return allPackagesOnfloor.value ? allPackagesNumber.value - packagesWithoutLocationNumber.value : 0
})

const packagesFullAutomatingNumber = computed(() => {
  return allPackagesOnfloor.value ?
    (packagesWithLocationNotStowedNumber.value >= packagesWithoutLocationNumber.value ?
      packagesWithLocationNotStowedNumber.value : packagesWithoutLocationNumber.value
    ) : 0
})

const inductPercentage = computed(() => {
  if (allPackagesNumber.value === 0) return 0
  return Math.round(((allPackagesNumber.value - packagesWithoutLocationNumber.value) / allPackagesNumber.value) * 100)
})

const stowPercentage = computed(() =>
  !allPackagesNumber.value || !inductPercentage.value
    ? 0
    : Math.round(
      ((allPackagesNumber.value - packagesWithLocationNotStowedNumber.value) / allPackagesNumber.value) * 100
    )
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
    'Induct': () => automaticInduct(true, false),
    'Stow': () => automaticInduct(false, true),
    'Full': () => automaticInduct(true, true),
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
  localStorage.removeItem(STORAGE_KEY_PAIR)
}

const updatePackagesData = (data) => {
  locations.value = data.value.locations
  allPackagesOnfloor.value.packagesWithoutLocation = data.value.packagesWithoutLocation
  allPackagesOnfloor.value.packagesWithLocationNotStowed = data.value.packagesWithLocationNotStowed
}

async function automaticInduct(induct = false, stow = false) {
  if (!induct && !stow) return

  automaticInductIsLoading.value = true
  resetLocalStorage()

  try {
    const { data, error } = await usePostFetch('/automaticInductAndStow', {
      induct,
      stow
    })

    if (error.value) {
      notifier('error', 'Automatic induct & stow', 'An error occurred')
      return
    }

    if (!data.value) {
      notifier('error', 'Automatic induct & stow', 'No data returned')
      return
    }

    const actions = []
    if (induct) actions.push('induct')
    if (stow) actions.push('stow')

    const title =
      actions.length === 2
        ? 'Induct & stow'
        : actions[0].charAt(0).toUpperCase() + actions[0].slice(1)

    const message =
      actions.length === 2
        ? 'The automatic induct & stow are finished'
        : `The automatic ${actions[0]} is finished`

    notifier('success', title, message)

    updatePackagesData(data)

  } finally {
    automaticInductIsLoading.value = false
    sidePanelRef.value?.toggleSidePanel()
  }
}

async function resetLocationsBagsPackages() {
  hardResetIsLoading.value = true;
  const { data, error } = await usePostFetch('/hardResetLocationsBagsPackages');

  if (data.value) {
    resetLocalStorage()
    hardResetIsLoading.value = false;
    notifier('success', 'Hard reset', `The reset is finished`)

    updatePackagesData(data)
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
