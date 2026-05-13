<template>
  <div class="grid grid-flow-col grid-rows-6 gap-4">

    <HorizontalLinkButton v-for="loc in orderedLocations" :key="loc.id" @click="scanBag(loc.bag)"
      :title="loc?.name || 'Location name'"
      :isDisabled="!loc.bag"
      :focused="isCurrentLoc(loc?.name) ? getBagColor(currentBag?.name) : 'text-gray-300 dark:text-gray-700/90'" />

  </div>
</template>

<script setup>
import { inject } from 'vue'
import HorizontalLinkButton from '../UI/Buttons/HorizontalLinkButton.vue';
import { useLogic } from '../../composables/useLogic.js';

const { getColor } = useLogic()

const props = defineProps({
  orderedLocations: Array,
});

const { currentBag, scanBag } = inject('picking')

const isCurrentLoc = (name) => currentBag.value?.location === name

const getBagColor = (name) => {
  const prefix = getColor(name);
  const colors = {
    'BLK': '',
    'NVY': 'outline-blue-700',
    'ORG': 'outline-orange-700',
    'YLO': 'outline-yellow-700',
    'GRN': 'outline-green-700',
  }
  return `outline-2 outline-offset-2 ${colors[prefix]}` ?? '';
}

</script>
