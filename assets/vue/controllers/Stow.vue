<template>
  <div class="flex flex-col gap-8">
    <button v-on:click="setCurrentPair(null)"><- Back</button>
        <BorderedContent v-if="!currentPair" title="Alleys">
          <div v-for="(pair, i) in locations" v-on:click="setCurrentPair(pair)">
            <h2 :class="alleyHasPackages(pair) ? 'text-red-500' : ''">{{ getPairName(i) }}</h2>
          </div>
        </BorderedContent>
        <BorderedContent v-if="currentPair" title="Package drop">
          <div class="relative w-full min-h-70 flex items-center justify-center">
            <transition name="fade-slide" tag="div" enter-active-class="transition-all duration-500 ease-out"
              enter-from-class="opacity-0" enter-to-class="opacity-100">
              <div class="absolute top-0 w-full z-10 cursor-pointer"
                v-on:click="setCurrentPackage(currentPairPackages[0])">
                <Package :package="currentPairPackages[0]" :borderColor="currentPackage ? 'border-blue-500' : ''" />
              </div>
            </transition>
            <!-- <p v-if="inductPackageLoading" class="animate-pulse">Wait for another package</p> -->
          </div>
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
  console.log('current Pair onmounted', currentPair.value);
})

const getPairName = (name) => {
  const [letter, num] = name.split('-');
  const n = Number(num);
  return `${letter}${n} & ${letter}${n + 1}`;
};

const getPairFirstLetter = (name) => {
  return `${name[0]}`
}

const setCurrentPair = (pair) => currentPair.value = pair

const setCurrentPackage = (pack) => currentPackage.value = pack

const alleyHasPackages = (pair) => {
  const ar = pair.flatMap(row => row.packages.filter(p => p.userStow === null))

  return ar.length !== 0 ? true : false
}

const isCurrentLoc = (name) => currentPackage.value?.location.name === name

const currentPairPackages = computed(() => {
  return currentPair.value.flatMap(row =>
    row.packages.filter(p => p.userStow === null)
  )
})

async function stowPackage(loc) {
  if (!currentPackage.value) {
    return
  }

  if (loc.name !== currentPackage.value.location.name) {
    console.log('Wrong Bag');
    return
  }

  const { data, error } = await usePostFetch(`/setUserStow/${currentPackage.value.id}`)

  console.log('fetch package', data);

}

const orderedLocations = computed(() => {
  const orderSpecs = [
    { floor: '1', side: '2' }, // col 1
    { floor: '1', side: '1' }, // col 2
    { floor: '2', side: '1' }, // col 3
    { floor: '2', side: '2' }, // col 4
  ]

  const letters = ['A', 'B', 'C', 'D', 'E', 'G']

  const byKey = new Map(
    currentPair.value.map(loc => [loc.name, loc])
  )

  const result = []

  for (const spec of orderSpecs) {
    for (const letter of letters) {
      const key = `B-${spec.floor}-${letter}-${spec.side}`
      const loc = byKey.get(key)
      if (loc) result.push(loc)
    }
  }

console.log('first', getPairFirstLetter(currentPair.value[0].name));
console.log('byKey', byKey);

console.log('result', result);



  return result
})



console.log('locations', locations.value);


provide('stow', {})

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
