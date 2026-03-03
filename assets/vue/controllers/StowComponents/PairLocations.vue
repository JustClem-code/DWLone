<template>
  <div class="grid grid-flow-col grid-rows-6 gap-4">

    <HorizontalLinkButton v-for="loc in orderedLocations" :key="loc.id" @click="stowPackage(loc)"
      :title="loc?.name || 'Location name'"
      :focused="isCurrentLoc(loc?.name) ? getBagColor(currentPackage.bag?.name) : 'text-gray-300 dark:text-gray-700/90'" />

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

const { currentPackage, stowPackage } = inject('stow')

const isCurrentLoc = (name) => currentPackage.value?.location?.name === name

const getBagColor = (name) => {
  const color = getColor(name)
  return color ? `outline-2 outline-${color}-700 outline-offset-2` : 'outline-2 outline-offset-2';
}

</script>
