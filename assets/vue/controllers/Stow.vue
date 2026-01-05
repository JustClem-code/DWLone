<template>
  <div class="flex flex-col gap-8">
    <button v-on:click="setCurrentPair(null)"><- Back</button>



        <BorderedContent v-if="!currentPair" title="Alleys">
          <div class="grid grid-flow-col grid-rows-13 gap-4">
            <div v-for="(pair, i) in locations" v-on:click="setCurrentPair(pair)" class="flex justify-center items-center w-full bg-white dark:bg-gray-800/50
                border rounded-md shadow-xs dark:shadow-none border-gray-300
                dark:border-gray-700/90 hover:border-gray-500 p-1 md:p-4 cursor-pointer
                ">
              <h3 class="text-xs md:text-base font-light md:font-semibold"
                :class="alleyHasPackages(pair) ? 'text-red-500' : ''">{{ getPairName(i) }}</h3>
            </div>
          </div>
        </BorderedContent>



        <BorderedContent v-if="currentPair" title="Package drop">
          <PackageDrop :packages="currentPairPackages" />
        </BorderedContent>

        <BorderedContent v-if="currentPair" title="Location">
          <div class="grid grid-flow-col grid-rows-6 gap-4">

            <div v-for="loc in orderedLocations" :key="loc.id">
              <div v-on:click="stowPackage(loc)" class="flex justify-center items-center w-full bg-white dark:bg-gray-800/50
                border rounded-md shadow-xs dark:shadow-none border-gray-300
                dark:border-gray-700/90 hover:border-gray-500 p-1 md:p-4 cursor-pointer
                "
                :class="{ 'animate-pulse': !loc }, isCurrentLoc(loc?.name) ? 'outline-2 outline-red-700 outline-offset-2' : ''">
                <h3 class="text-sm md:text-base font-light md:font-semibold">{{ loc?.name || 'Location name' }}</h3>
              </div>
            </div>

          </div>
        </BorderedContent>



  </div>
</template>

<script setup>

import { onMounted, watch, ref, provide, computed } from 'vue'
import BorderedContent from './UI/BorderedContent.vue'

import { useFetch, usePostFetch } from '../composables/fetch.js'
import { useNotification } from '../composables/eventBus.js'
import Package from './UI/Package.vue'
import PackageDrop from './StowComponents/PackageDrop.vue'

const { data: locations, error: errorDock } = useFetch('/getlocations')

const { notifier } = useNotification()

const dockingData = ref(null)
const dockingError = ref(null)
const dockingIsLoading = ref(false)

const STORAGE_KEY = 'currentPair'

const currentPair = ref(null)
const currentPackage = ref(null)

onMounted(() => {
  const raw = localStorage.getItem(STORAGE_KEY)
  currentPair.value = raw ? JSON.parse(raw) : null
})

const getPairName = (name) => {
  const [letter, num] = name.split('-');
  const n = Number(num);
  return `${letter}${n} & ${letter}${n + 1}`;
};

const firstAisleNumber = computed(() => {
  const match = currentPair.value[0].name.match(/\d+/);
  return match ? parseInt(match[0], 10) : null;
})

const pairFirstLetter = computed(() => {
  return `${currentPair.value[0].name[0]}`
})

const setCurrentPair = (pair) => currentPair.value = pair

const setCurrentPackage = (pack) => currentPackage.value = pack

const alleyHasPackages = (pair) => {
  const ar = pair.flatMap(row => row.packages.filter(p => p.userStow === null))
  return ar.length !== 0 ? true : false
}

const orderedLocations = computed(() => {
  const orderSpecs = [
    { floor: `${firstAisleNumber.value}`, side: '2' }, // col 1
    { floor: `${firstAisleNumber.value}`, side: '1' }, // col 2
    { floor: `${firstAisleNumber.value + 1}`, side: '1' }, // col 3
    { floor: `${firstAisleNumber.value + 1}`, side: '2' }, // col 4
  ]

  const letters = ['A', 'B', 'C', 'D', 'E', 'G']

  const byKey = new Map(
    currentPair.value.map(loc => [loc.name, loc])
  )

  const result = []

  for (const spec of orderSpecs) {
    for (const letter of letters) {
      const key = `${pairFirstLetter.value}-${spec.floor}-${letter}-${spec.side}`
      const loc = byKey.get(key)
      if (loc) result.push(loc)
    }
  }

  return result
})

const isCurrentLoc = (name) => currentPackage.value?.location.name === name

const currentPairPackages = computed(() => {
  return currentPair.value.flatMap(row =>
    row.packages.filter(p => p.userStow === null)
  )
})

const updateCurrentPairPackages = () => {
  // TODO: mettre aussi à jour locations
  currentPair.value = currentPair.value.map(row => ({
    ...row,
    packages: row.packages.filter(p => p.id !== currentPackage.value.id)
  }))

  /* if (locations.value) {
    locations.value = locations.value.map(pair =>
      pair.map(row => ({
        ...row,
        packages: row.packages.filter(p => p.id !== currentPackage.value.id)
      }))
    )
  } */
  /*  if (currentPairPackages.value.length === 0) {
     const palletOnFloorIndex = palletsOnFloorWithPackages.value.findIndex(p => p.id === currentPallet.value.id)
     palletsOnFloorWithPackages.value.splice(palletOnFloorIndex, 1)
   } */
}

async function stowPackage(loc) {
  if (!currentPackage.value) {
    return
  }

  if (loc.name !== currentPackage.value.location.name) {
    console.log('Wrong Bag');
    return
  }

  const { data, error } = await usePostFetch(`/setUserStow/${currentPackage.value.id}`)

  if (data.value) {
    currentPackage.value = data.value
    updateCurrentPairPackages()
    /*  setTimeout(() => {
       setLocationLoading.value = false;
     }, 500);
     setTimeout(() => {
       notifier('success', 'Induction', `The package (Id: ${currentPackage.value.id}) is inducted`)
     }, 1000);
     setTimeout(() => {
       currentPackage.value = null
     }, 1500); */
  }

  console.log('fetch package', data);
  console.log('currentPairPackages', currentPairPackages.value);
  console.log('currentPair', currentPair.value);
  console.log('locations', locations.value);

}


provide('stow', { setCurrentPackage, currentPackage })

watch(
  currentPair,
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
