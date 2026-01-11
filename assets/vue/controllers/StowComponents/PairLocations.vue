<template>
  <div class="grid grid-flow-col grid-rows-6 gap-4">

    <div v-for="loc in orderedLocations" :key="loc.id">
      <div v-on:click="stowPackage(loc)" class="flex justify-center items-center w-full bg-white dark:bg-gray-800/50
                border rounded-md shadow-xs dark:shadow-none border-gray-300
                dark:border-gray-700/90 hover:border-gray-500 p-1 md:p-4 cursor-pointer
                "
        :class="{ 'animate-pulse': !loc }, isCurrentLoc(loc?.name) ? 'outline-2 outline-red-700 outline-offset-2' : ''">
        <h3 class="text-sm md:text-base font-light md:font-semibold">{{ loc?.name || 'Location name' }}</h3>
      </div>
    </div>

  </div>
</template>

<script setup>
import { inject } from 'vue'

const props = defineProps({
  orderedLocations: Array,
});

const { currentPackage, stowPackage } = inject('stow')

const isCurrentLoc = (name) => currentPackage.value?.location?.name === name

</script>
