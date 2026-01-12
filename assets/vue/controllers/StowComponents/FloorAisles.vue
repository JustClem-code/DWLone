<template>
  <div class="grid grid-flow-col grid-rows-13 gap-4">

    <HorizontalLinkButton v-for="(pair, i) in locations" :key="pair" @click="setCurrentPair(pair)"
      :title="getPairName(i)" :focused="alleyHasPackages(pair) ? 'text-red-500' : ''" />

  </div>
</template>

<script setup>
import { inject } from 'vue'
import HorizontalLinkButton from '../UI/Buttons/HorizontalLinkButton.vue';

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
