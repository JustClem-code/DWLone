<template>
  <div class="grid grid-flow-col grid-rows-13 gap-4">

    <HorizontalLinkButton v-for="(pair) in locations" :key="pair" @click="setCurrentPair(pair)"
      :title="pair.id" :focused="alleyHasPackages(pair) ? 'text-blue-400' : ''" :pingfocused="alleyHasPackages(pair)" />

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
