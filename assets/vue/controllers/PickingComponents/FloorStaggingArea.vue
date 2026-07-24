<template>
  <div class="grid grid-flow-col grid-rows-2 gap-4">

    <div v-for="(groupe, indexGroup) in staggingAreas" :key="indexGroup"
      class="grid grid-cols-4 gap-1 sm:gap-4 justify-center px-2 md:px-4">

      <HorizontalLinkButton v-for="(staggingArea) in groupe" :key="staggingArea.id" @click="scanStaggingArea(staggingArea)"
        :title="staggingArea.name"
        :focused="focusedPairs.has(staggingArea.id) ? 'text-blue-400' : 'text-gray-300 dark:text-gray-700/90'"
        :pingfocused="focusedPairs.has(staggingArea.id)" :isDisabled="disabledButton" :isLoading="globalLoading" />

    </div>

  </div>

</template>

<script setup>
import { inject, computed } from 'vue'
import HorizontalLinkButton from '../UI/Buttons/HorizontalLinkButton.vue';

const props = defineProps({
  staggingAreas: Object,
});

const emit = defineEmits(['click'])

const { currentRoadPart, scanStaggingArea, allBagsPicked, globalLoading } = inject('picking')

const disabledButton = computed(() => {
  return !allBagsPicked.value && currentRoadPart.value.cart !== null
})

const focusedPairs = computed(() => {

  const set = new Set()

  for (const groupe of Object.values(props.staggingAreas ?? {})) {
    for (const staggingArea of groupe) {
      const isFocused = staggingArea.name === currentRoadPart.value?.stagging?.name;

      if (isFocused) {
        set.add(staggingArea.id)
        console.log('staggingArea.id', staggingArea.id);

      }
    }
  }

  return set
})

</script>
