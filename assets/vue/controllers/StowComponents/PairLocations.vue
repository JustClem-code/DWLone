<template>
  <div class="grid grid-flow-col grid-rows-6 gap-4">

    <HorizontalLinkButton v-for="loc in orderedLocations" :key="loc.id" @click="stowPackage(loc)"
      :title="loc?.name || 'Location name'"
      :focused="isCurrentLoc(loc?.name) ? getBagColor(currentPackage.bag?.name) : ''" />

  </div>
</template>

<script setup>
import { inject } from 'vue'
import HorizontalLinkButton from '../UI/Buttons/HorizontalLinkButton.vue';

const props = defineProps({
  orderedLocations: Array,
});

const { currentPackage, stowPackage } = inject('stow')

const isCurrentLoc = (name) => currentPackage.value?.location?.name === name

const getBagColor = (name) => {
  const prefix = name.match(/^[^-]+/)[0];
  const colors = {
    'BLK': 'outline-2 outline-offset-2',
    'NVY': 'outline-2 outline-blue-700 outline-offset-2',
    'ORG': 'outline-2 outline-orange-700 outline-offset-2',
    'YLO': 'outline-2 outline-yellow-700 outline-offset-2',
    'GRN': 'outline-2 outline-green-700 outline-offset-2',
  }
  return colors[prefix] ?? '';
}

</script>
