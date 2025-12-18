<template>
  <div class="flex flex-col gap-8">
    <BorderedContent title="Pallet 5S">
      <Pallet5S :currentPallet="currentPallet" :currentPackage="currentPackage"></Pallet5S>
    </BorderedContent>

    <BorderedContent title="ASML">
      <Conveyor :currentPackage="currentPackage" />
    </BorderedContent>
  </div>
</template>

<script setup>

import { ref, computed, provide, watch, onMounted } from 'vue'
import { useFetch, usePostFetch } from '../composables/fetch.js'
import { useLogic } from '../composables/useLogic.js'
import { useNotification } from '../composables/eventBus.js'

import BorderedContent from './UI/BorderedContent.vue'
import Pallet5S from './InductionComponents/Pallet5S.vue'
import Conveyor from './InductionComponents/Conveyor.vue'

const { notifier } = useNotification()

const { data: palletsOnFloorWithPackages, error: errorPallet } = useFetch('/getpalletsonfloorwithpackages')

const { getNumberOfPackagesNotInducted } = useLogic()

const addPalletLoading = ref(false)
const setLocationLoading = ref(false)

const STORAGE_KEY = 'currentPallet'

const currentPallet = ref(null)
const currentPackage = ref(null)

onMounted(() => {
  const raw = localStorage.getItem(STORAGE_KEY)
  currentPallet.value = raw ? JSON.parse(raw) : null
  console.log('current pallet onmounted', currentPallet.value);
})


const addPallet = (val) => {
  addPalletLoading.value = true
  setTimeout(() => {
    currentPallet.value = val
    addPalletLoading.value = true
    notifier('success', 'Add Pallet', `The pallet (Id: ${currentPallet.value.id}) is in 5S location`)
    addPalletLoading.value = false
  }, 1000);
}

const palletsOnfloorOptions = computed(() => {
  return palletsOnFloorWithPackages.value?.filter(p => p.id !== currentPallet.value?.id)
})

provide('induction', { palletsOnfloorOptions, addPallet, addPalletLoading, setLocation, setLocationLoading, resetLocationsBagsPackages })

const updateCurrentPallet = () => {
  currentPallet.value.packages = currentPallet.value.packages.filter((i) => i.id !== currentPackage.value.id);
  if (getNumberOfPackagesNotInducted(currentPallet.value) === 0) {
    const palletOnFloorIndex = palletsOnFloorWithPackages.value.findIndex(p => p.id === currentPallet.value.id)
    palletsOnFloorWithPackages.value.splice(palletOnFloorIndex, 1)
  }
}

async function resetLocationsBagsPackages() {
  const { data, error } = await usePostFetch('/resetLocationsBagsPackages');
  currentPallet.value = null
  palletsOnFloorWithPackages.value = data.value
}

async function setLocation(inductedPackage) {
  setLocationLoading.value = true;

  const { data, error } = await usePostFetch(`/setLocation/${inductedPackage.id}`)

  if (data.value) {
    currentPackage.value = data.value
    updateCurrentPallet()
    setTimeout(() => {
      setLocationLoading.value = false;
    }, 500);
    setTimeout(() => {
      notifier('success', 'Induction', `The package (Id: ${currentPackage.value.id}) is inducted`)
    }, 1000);
    setTimeout(() => {
      currentPackage.value = null
    }, 1500);
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
