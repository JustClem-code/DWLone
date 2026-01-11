<template>
  <div class="grid grid-flow-col grid-rows-13 gap-4">
    <div v-for="(pair, i) in locations" v-on:click="setCurrentPair(pair)" class="flex justify-center items-center w-full bg-white dark:bg-gray-800/50
                border rounded-md shadow-xs dark:shadow-none border-gray-300
                dark:border-gray-700/90 hover:border-gray-500 p-1 md:p-4 cursor-pointer"
                >
      <h3 class="text-xs md:text-base font-light md:font-semibold"
        :class="alleyHasPackages(pair) ? 'text-red-500' : ''">{{ getPairName(i) }}</h3>
    </div>
  </div>
</template>

<script setup>
import { inject } from 'vue'

const props = defineProps({
  locations: Object,
});

const { setCurrentPair } = inject('stow')

const getPairName = (name) => {
  const [letter, num] = name.split('-');
  const n = Number(num);
  return `${letter}${n} & ${letter}${n + 1}`;
};

const alleyHasPackages = (pair) => {
  const ar = pair.flatMap(row => row.packages.filter(p => p.userStow === null))
  return ar.length !== 0 ? true : false
}
</script>
