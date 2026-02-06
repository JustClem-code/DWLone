<template>
  <div class="flex flex-col gap-8">
    <div class="flex gap-8">
      <BorderedContent title="Welcome" width="w-1/2">
        <div>
          <h2 class="text-2xl">
            Hello {{ userName ?? 'John Doe' }}!
          </h2>
        </div>
      </BorderedContent>
      <BorderedContent title="Auto" width="w-1/2">
        <div class="flex flex-col gap-2">

          <RadioCard v-for="option in automaticOptions" :key="option.value" :option="option" v-model="selected" />

          <BaseButton @click="submitAutomaticForm" title="Automatic program" styleColor="primary"
            :isDisabled="!selected" :isLoading="hardResetIsLoading || automaticInductIsLoading" />

        </div>
      </BorderedContent>
    </div>
    <BorderedContent title="Bags">
      <div class="grid grid-cols-6 gap-4">
        <HorizontalLinkButton v-for="location in locations" :key="location.id" @click="setCurrentBag(location.bag)"
          :title="location.name" :focused="location.bag?.packages?.length > 0 ? getBagColor(location.bag?.name) : ''" />
      </div>
    </BorderedContent>

    <DialogComponentSlot ref="infoDialogRef" :hasCloseCross="true">
      <InformationComponent :informations="bagInfos" />
    </DialogComponentSlot>
  </div>
</template>

<script setup>

//TODO:

// STAT UI : https://tailwindcss.com/plus/ui-blocks/application-ui/data-display/stats
// PROGRESS BAR : https://tailwindcss.com/plus/ui-blocks/application-ui/navigation/progress-bars
// DRAWERS : https://tailwindcss.com/plus/ui-blocks/application-ui/overlays/drawers

// travailler l'UI de la page dashboard

// Filtrer en fonctions des locations vides
// calculer le nombre de bag plein avec des packages
// afficher les stats des pallet attentu, packages inducté et en stow, ect...

// WIP : Revoir les method de repository to array pour limiter les données inutiles ou dupliquée

// gestion du animate pulse si chargement de la (list / HorizontalLinkButton et divers composants) => voir tailwind UI
// plus globalement, il faut gérer le chargement côté vue et twig

import { onMounted, watch, ref, provide, computed } from 'vue'

import { userStore } from '../composables/userStore.js'
import { useFetch, usePostFetch } from '../composables/fetch.js'
import { useLogic } from '../composables/useLogic.js'
import { useNotification } from '../composables/eventBus.js'

import BorderedContent from './UI/BorderedContent.vue';
import HorizontalLinkButton from './UI/Buttons/HorizontalLinkButton.vue';
import DialogComponentSlot from './UI/Modals/DialogComponentSlot.vue';
import InformationComponent from './UI/Modals/InformationComponent.vue';
import BaseButton from './UI/Buttons/BaseButton.vue';
import RadioCard from './UI/Radios/RadioCard.vue'

const props = defineProps({
  is_user: Boolean,
  user_name: String
});


const { userName } = userStore()

const { data: locations, error: errorLocations } = useFetch('/getBagsInLocations')
const { data: allPackagesOnfloor, error: errorAllPackages } = useFetch('/getAllPackagesOnFloor')

const { formatInt } = useLogic()

const { notifier } = useNotification()

onMounted(() => {
  console.log(`the component is now mounted.`)
})

const currentBag = ref(null)
const infoDialogRef = ref(null)
const selected = ref(null)

const STORAGE_KEY_PALLET = 'currentPallet'
const STORAGE_KEY_PAIR = 'currentPair'

const automaticInductIsLoading = ref(null)
const hardResetIsLoading = ref(null)

const packagesWithoutLocationNumber = computed(() => { return allPackagesOnfloor.value ? allPackagesOnfloor.value.packagesWithoutLocation.length : 0 })
const packagesWithLocationNotStowedNumber = computed(() => { return allPackagesOnfloor.value ? allPackagesOnfloor.value.packagesWithLocationNotStowed.length : 0 })
const packagesToResetNumber = computed(() => { return allPackagesOnfloor.value ? allPackagesOnfloor.value.allPackages.length - packagesWithoutLocationNumber.value : 0 })
const packagesFullAutomatingNumber = computed(() => { return allPackagesOnfloor.value ? (packagesWithLocationNotStowedNumber.value >= packagesWithoutLocationNumber.value ? packagesWithLocationNotStowedNumber.value : packagesWithoutLocationNumber.value) : 0 })

const automaticOptions = computed(() => [
  { 'value': 'Induct', 'notice': 'Automating of pallet induct on floor', 'number': `${packagesWithoutLocationNumber.value}`, 'disabled': packagesWithoutLocationNumber.value === 0 },
  { 'value': 'Stow', 'notice': 'Automating of packages stow', 'number': `${packagesWithLocationNotStowedNumber.value}`, 'disabled': packagesWithLocationNotStowedNumber.value === 0 },
  { 'value': 'Full', 'notice': 'Automating every step', 'number': `${packagesFullAutomatingNumber.value}`, 'disabled': packagesFullAutomatingNumber.value === 0 },
  { 'value': 'Hard reset', 'notice': 'Reset all steps', 'number': `${packagesToResetNumber.value}`, 'disabled': packagesToResetNumber.value === 0 },
])

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

  }
}

function submitAutomaticForm() {
  const actions = {
    'Induct':      () => automaticInduct(true, false),
    'Stow':        () => automaticInduct(false, true),
    'Full':        () => automaticInduct(true, true),
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


watch(
  () => locations.value,
  (newVal, oldVal) => {
    console.log('locations changed:', newVal, oldVal)
  })

</script>
