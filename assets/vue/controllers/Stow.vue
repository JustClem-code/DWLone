<template>
  <div class="flex flex-col gap-8">
    <button v-on:click="setCurrentPair(null)"><- Back</button>
        <BorderedContent v-if="!currentPair" title="Alleys">
          <div v-for="(pair, i) in locations" v-on:click="setCurrentPair(pair)">
            <h2 :class="alleyHasPackages(pair) ? 'text-red-500' : ''">{{ getPairName(i) }}</h2>
          </div>
        </BorderedContent>
        <BorderedContent v-if="currentPair" title="sorting bin">
          <div class="relative w-full min-h-70 flex items-center justify-center">
            <transition name="fade-slide" tag="div" enter-active-class="transition-all duration-500 ease-out"
              enter-from-class="opacity-0" enter-to-class="opacity-100">
              <div draggable="true" class="absolute top-0 w-full z-10 cursor-pointer">
                <Package :package="currentPairPackages[0]" borderColor="border-blue-500" />
              </div>
            </transition>
            <!-- <p v-if="inductPackageLoading" class="animate-pulse">Wait for another package</p> -->
          </div>
        </BorderedContent>
        <BorderedContent v-if="currentPair" title="Location">
          <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">

            <div v-for="loc in currentPair">
              <div
                class="flex justify-center items-center w-full bg-white dark:bg-gray-800/50 border border-0 dark:border-1 rounded-md shadow-sm dark:shadow-none dark:border-gray-700/90 p-4"
                :class="{ 'animate-pulse': !loc }">
                <h3 class="text-base font-semibold">{{ loc?.name || 'Location name' }}</h3>
              </div>
            </div>

          </div>
        </BorderedContent>



  </div>
</template>

<script setup>

import { ref, provide, computed } from 'vue'
import BorderedContent from './UI/BorderedContent.vue'

import { useFetch, usePostFetch } from '../composables/fetch.js'
import { useNotification } from '../composables/eventBus.js'
import Package from './UI/Package.vue'

const { data: locations, error: errorDock } = useFetch('/getlocations')

const { notifier } = useNotification()

const dockingData = ref(null)
const dockingError = ref(null)
const dockingIsLoading = ref(false)

const currentPair = ref(null)

const getPairName = (name) => {
  const [letter, num] = name.split('-');
  const n = Number(num);
  return `${letter}${n} & ${letter}${n + 1}`;
};

const setCurrentPair = (pair) => currentPair.value = pair

const alleyHasPackages = (pair) => {
  const ar = pair.flatMap(row => row.packages.filter(p => p.userStow === null))

  return ar.length !== 0 ? true : false
}

const currentPairPackages = computed(() => {
  return currentPair.value.flatMap(row =>
    row.packages.filter(p => p.userStow === null)
  )
})

console.log('locations', locations.value);


provide('stow', {})

</script>
