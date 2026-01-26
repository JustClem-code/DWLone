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

          <p>Valeur sélectionnée : {{ selected }}</p>

          <BaseButton @click="submitAutomaticForm" title="Automatic program" styleColor="primary" :isDisabled="!selected"/>

          <BaseButton @click="automaticInduct" title="Automatic" styleColor="primary"
            :isLoading="automaticInductIsLoading" />
          <BaseButton @click="resetLocationsBagsPackages" title="Hard reset" styleColor="warning"
            :isLoading="hardResetIsLoading" />
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

// créer un script de stow automatique
// -> côté vue, faire en sorte de choisir des options avec radios button par exemple 'induct' 'stow' ou les deux

// Filtrer en fonctions des locations vides
// calculer le nombre de bag plein avec des packages
// afficher les stats des pallet attentu, packages inducté et en stow, ect...

// WIP : Revoir les method de repository to array pour limiter les données inutiles ou dupliquée

// régler les problèmes ERROR lens (package...)
// gestion du animate pulse si chargement de la (list / HorizontalLinkButton et divers composants) => voir tailwind UI
// plus globalement, il faut gérer le chargement côté vue et twig

import { onMounted, ref, provide, computed } from 'vue'

import { userStore } from '../composables/userStore.js'
import { useFetch, usePostFetch } from '../composables/fetch.js'
import { useLogic } from '../composables/useLogic.js'
import { useNotification } from '../composables/eventBus.js'

import BorderedContent from './UI/BorderedContent.vue';
import HorizontalLinkButton from './UI/Buttons/HorizontalLinkButton.vue';
import DialogComponentSlot from './UI/Modals/DialogComponentSlot.vue';
import InformationComponent from './UI/Modals/InformationComponent.vue';
import BaseButton from './UI/Buttons/BaseButton.vue';
import CheckCircleFillIcon from './UI/Icons/CheckCircleFillIcon.vue'
import RadioCard from './UI/Radios/RadioCard.vue'

const props = defineProps({
  is_user: Boolean,
  user_name: String
});


const { userName } = userStore()

const { data: locations, error: errorLocations } = useFetch('/getBagsInLocations')

const { formatInt } = useLogic()

const { notifier } = useNotification()

onMounted(() => {
  console.log(`the component is now mounted.`)
  console.log(`location`, locations)
})

const currentBag = ref(null)
const infoDialogRef = ref(null)
const selected = ref(null)

const STORAGE_KEY_PALLET = 'currentPallet'
const STORAGE_KEY_PAIR = 'currentPair'

const automaticInductIsLoading = ref(null)
const hardResetIsLoading = ref(null)

const automaticOptions = [
  { 'value': 'Induct', 'notice': 'Automating of pallet induct on floor', 'number': '' },
  { 'value': 'Stow', 'notice': 'Automating of packages stow', 'number': '' },
  { 'value': 'Full', 'notice': 'Automating every step', 'number': '' },
  { 'value': 'Hard reset', 'notice': 'Reset all steps', 'number': '' },
]

const setCurrentBag = (bag) => {
  if (!bag) {
    return
  }
  currentBag.value = bag;
  infoDialogRef.value?.openDialog()
}

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

async function submitAutomaticForm() {
  console.log('selectedOption', selected.value);

}

const resetLocalStorage = () => {
  localStorage.removeItem(STORAGE_KEY_PALLET)
  localStorage.removeItem(STORAGE_KEY_PAIR)
}

async function automaticInduct() {
  automaticInductIsLoading.value = true;
  resetLocalStorage()
  const { data, error } = await usePostFetch('/automaticInductAndStow')

  if (data.value) {
    automaticInductIsLoading.value = false;
    notifier('success', 'Induct', `The automatic induct is finished`)
    locations.value = data.value
  }

}

async function resetLocationsBagsPackages() {
  hardResetIsLoading.value = true;
  const { data, error } = await usePostFetch('/hardResetLocationsBagsPackages');

  if (data.value) {
    resetLocalStorage()
    hardResetIsLoading.value = false;
    notifier('success', 'Hard reset', `The reset is finished`)
    locations.value = data.value
  }
}

</script>
