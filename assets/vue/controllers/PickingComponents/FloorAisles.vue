<template>
  <div class="grid grid-cols-4 divide-x divide-gray-200 dark:divide-gray-700/90">

    <div v-for="(groupe, indexGroup) in locations" :key="indexGroup"
      class="flex flex-col gap-1 sm:gap-4 justify-center px-2 md:px-4">

      <HorizontalLinkButton v-for="(pair) in groupe" :key="pair" @click="setCurrentPair(pair)" :title="pair.id"
        :focused="alleyHasCurrentBag(pair) ? 'text-blue-400' : 'text-gray-300 dark:text-gray-700/90'"
        :pingfocused="alleyHasCurrentBag(pair)" />

    </div>

  </div>

</template>

<script setup>
import { inject, onMounted, watch } from 'vue'
import HorizontalLinkButton from '../UI/Buttons/HorizontalLinkButton.vue';

const props = defineProps({
  locations: Object,
});

const { setCurrentPair, currentBag } = inject('picking')

const alleyHasCurrentBag = (pair) => {
  return pair.locations.some(l => l.name === currentBag.value?.location)
}

watch(
  () => props.locations,
  (val) => {
    console.log('locations watch:', val);
  },
  { immediate: true, deep: true }
);
</script>
