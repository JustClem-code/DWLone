<template>
  <div class="grid grid-cols-4 divide-x divide-gray-200 dark:divide-gray-700/90">

    <div v-for="(groupe, indexGroup) in staggingAreas" :key="indexGroup" class="flex flex-col gap-1 sm:gap-4 justify-center px-2 md:px-4">

      <HorizontalLinkButton v-for="(staggingArea) in groupe" :key="staggingArea" @click="action(staggingArea)" :title="staggingArea.name"
        :focused="alleyHasPackages(staggingArea) ? 'text-blue-400' : 'text-gray-300 dark:text-gray-700/90'"
        :pingfocused="alleyHasPackages(staggingArea)" />

    </div>

  </div>

</template>

<script setup>
import { inject } from 'vue'
import HorizontalLinkButton from '../UI/Buttons/HorizontalLinkButton.vue';

const props = defineProps({
  staggingAreas: Object,
});

const action = (staggingArea) => { console.log('click on stagging area', staggingArea.name);
}

// const { setCurrentPair } = inject('picking')

const alleyHasPackages = (pair) => {
  // const ar = pair.locations.flatMap(row => row.packages.filter(p => p.userStow === null))
  return true
}
</script>
