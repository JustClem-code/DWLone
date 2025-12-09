<template>
  <div class="flex flex-col gap-8">
    <Pallet5S :currentPallet="currentPallet"></Pallet5S>

    <BorderedContent title="ASML">
      <div class="flex flex-col gap-4">
        <Conveyor />
      </div>
    </BorderedContent>
  </div>
</template>

<script setup>

import { ref, provide, computed, watch, onMounted } from 'vue'
import BorderedContent from './UI/BorderedContent.vue'

import { useFetch, usePostFetch } from '../composables/fetch.js'
import emitter from '../composables/eventBus.js'
import Pallet5S from './InductionComponents/Pallet5S.vue'
import Conveyor from './InductionComponents/Conveyor.vue'

const notifier = (type, message, message_2) => {
  emitter.emit('notify', { type: type, message: message, message_2: message_2 })
}

const { data: palletsOnFloor, error: errorPallet } = useFetch('/getpalletsonfloor')

const setLocationData = ref(null)

const addPalletLoading = ref(false)
const setLocationLoading = ref(false)

const STORAGE_KEY = 'currentPallet'

const currentPallet = ref(null)

onMounted(() => {
  const raw = localStorage.getItem(STORAGE_KEY)
  currentPallet.value = raw ? JSON.parse(raw) : null
  console.log('current pallet onmounter', currentPallet.value);

})


const addPallet = (val) => {
  addPalletLoading.value = true
  setTimeout(() => {
    currentPallet.value = val
    addPalletLoading.value = true
    console.log('addPallet', val);
    notifier('success', 'Add Pallet', `The pallet (Id: ${currentPallet.value.id}) is in 5S location`)
    addPalletLoading.value = false
  }, 1000);

}

const getPackagesNotInducted = (pallet) => {
  return pallet.packages.filter(p => p.location === null);
}

provide('induction', { palletsOnFloor, addPalletLoading, addPallet, setLocation, resetLocationsBagsPackages, getPackagesNotInducted })

console.log("unloaded pallet", palletsOnFloor);


const updateListElements = () => {

  currentPallet.value.packages = currentPallet.value.packages.filter((i) => i.id !== setLocationData.value.id);

  console.log('updta', setLocationData.value.id);


}

async function resetLocationsBagsPackages() {
  const { data, error } = await usePostFetch('/resetLocationsBagsPackages');
  currentPallet.value = null
  console.log('datareset', data);
}

async function setLocation(inductedPackage) {
  setLocationLoading.value = true;

  const { data, error } = await usePostFetch(`/setLocation/${inductedPackage.id}`)

  setLocationData.value = data.value

  if (data.value) {
    updateListElements()
    setLocationLoading.value = false;
    notifier('success', 'Induction', `The package (Id: ${setLocationData.value.id}) is inducted`)
  }
}

watch(
  currentPallet,
  (val) => {
    if (val === null) {
      localStorage.removeItem(STORAGE_KEY)
    } else {
      localStorage.setItem(STORAGE_KEY, JSON.stringify(val))
    }
  },
  { deep: true }
)

</script>
