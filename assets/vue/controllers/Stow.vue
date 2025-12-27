<template>
  <div class="flex flex-col gap-8">
    <BorderedContent title="Location">
      <div v-if="!currentPair">
        <div v-for="(pair, i) in locations" v-on:click="setCurrentPair(pair)">
          <h2 :class="alleyHasPackages(pair) ? 'text-red-500' : ''">{{ getPairName(i) }}</h2>
        </div>
      </div>
      <div v-if="currentPair">
        <button v-on:click="setCurrentPair(null)"><- Back</button>
            <div v-for="loc in currentPair">{{ loc.name }}</div>
            <div v-for="item in currentPairPackages" :key="item.id">
              {{ item.id }}
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
  return ar === [] ? true : false
}

const currentPairPackages = computed(() => {
  return currentPair.value.flatMap(row =>
    row.packages.filter(p => p.userStow === null)
  )
})

console.log('locations', locations.value);


provide('stow', {})

</script>
