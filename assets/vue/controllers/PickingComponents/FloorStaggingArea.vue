<template>
  <div class="grid grid-flow-col grid-rows-2 gap-4">

    <div v-for="(groupe, indexGroup) in staggingAreas" :key="indexGroup" class="grid grid-cols-4 gap-1 sm:gap-4 justify-center px-2 md:px-4">

      <HorizontalLinkButton v-for="(staggingArea) in groupe" :key="staggingArea" @click="action(staggingArea)" :title="staggingArea.name"
        :focused="stagginAreaHighlighted(staggingArea) ? 'text-blue-400' : 'text-gray-300 dark:text-gray-700/90'"
        :pingfocused="stagginAreaHighlighted(staggingArea)" :isDisabled="globalLoading"/>

    </div>

  </div>

</template>

<script setup>
import { inject } from 'vue'
import HorizontalLinkButton from '../UI/Buttons/HorizontalLinkButton.vue';

const props = defineProps({
  staggingAreas: Object,
  roadPartStagging: Object,
  globalLoading: Boolean
});

const emit = defineEmits(['click'])

const { scanStaggingArea } = inject('picking')

const action = (staggingArea) => { scanStaggingArea(staggingArea);
}

const stagginAreaHighlighted = (staggingArea) => {
  return props.roadPartStagging.name === staggingArea.name
}
</script>
