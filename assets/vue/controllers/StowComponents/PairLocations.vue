<template>
  <div class="grid grid-flow-col grid-rows-6 gap-4">

    <HorizontalLinkButton v-for="loc in orderedLocations" :key="loc.id" @click="stowPackage(loc)"
      :title="loc?.name || 'Location name'"
      :focused="isCurrentLoc(loc?.name) ? 'outline-2 outline-green-700 outline-offset-2' : ''" />

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

</script>
