<template>
  <div class="grid grid-cols-4 divide-x divide-gray-200 dark:divide-gray-700/90">

    <div v-for="(groupe, indexGroup) in locations" :key="indexGroup" class="flex flex-col gap-1 sm:gap-4 justify-center px-2 md:px-4">

      <HorizontalLinkButton v-for="(pair) in groupe" :key="pair" @click="setCurrentPair(pair)" :title="pair.id"
        :focused="alleyHasPackages(pair) ? 'text-blue-400' : 'text-gray-300 dark:text-gray-700/90'"
        :pingfocused="alleyHasPackages(pair)" />

    </div>

  </div>

</template>

<script setup>
import { inject } from 'vue'
import HorizontalLinkButton from '../UI/Buttons/HorizontalLinkButton.vue';

const props = defineProps({
  locations: Object,
});

const { setCurrentPair } = inject('stow')

const alleyHasPackages = (pair) => {
  const ar = pair.locations.flatMap(row => row.packages.filter(p => p.userStow === null))
  return ar.length !== 0
}
</script>
