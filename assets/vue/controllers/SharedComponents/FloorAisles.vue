<template>
  <div class="grid grid-cols-4 divide-x divide-gray-200 dark:divide-gray-700/90">
    <div v-for="(groupe, indexGroup) in locations" :key="indexGroup"
      class="flex flex-col gap-1 sm:gap-4 justify-center px-2 md:px-4">
      <HorizontalLinkButton v-for="pair in groupe" :key="pair.id" @click="clickButton(pair)" :title="pair.id"
        :focused="focusedPairs.has(pair.id) ? 'text-blue-400' : 'text-gray-300 dark:text-gray-700/90'"
        :pingfocused="focusedPairs.has(pair.id)" />
    </div>
  </div>
</template>

<script setup>
import { inject, computed, watch } from 'vue'
import HorizontalLinkButton from '../UI/Buttons/HorizontalLinkButton.vue'

const props = defineProps({
  locations: Object,
  currentBag: Object
})

const emit = defineEmits(['click']);

const clickButton = (pair) => {
  emit('click', pair);
}

const focusedPairs = computed(() => {
  const currentLocation = props.currentBag?.location
  const set = new Set()

  for (const groupe of Object.values(props.locations ?? {})) {
    for (const pair of groupe) {
      const isFocused = pair.locations?.some((location) =>
        location.name === currentLocation ||
        location.packages?.some((p) => p.userStow === null)
      )

      if (isFocused) {
        set.add(pair.id)
      }
    }
  }

  return set
})

watch(
  () => props.locations,
  (val) => {
    console.log('locations watch:', val)
  },
  { immediate: true, deep: true }
)
</script>
