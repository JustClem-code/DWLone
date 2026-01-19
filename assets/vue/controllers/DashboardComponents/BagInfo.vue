<template>
  <h3 class="pb-6 border-b border-gray-200 dark:border-gray-700/90">Bag informations</h3>
  <dl class="divide-y divide-gray-200 dark:divide-gray-700/90">

    <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
      <dt class="text-sm/6 font-medium">Bag</dt>
      <dd class="mt-1 text-sm/6 text-gray-800 dark:text-gray-400 sm:col-span-2 sm:mt-0">{{ currentBag?.name }}</dd>
    </div>

    <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
      <dt class="text-sm/6 font-medium">Location</dt>
      <dd class="mt-1 text-sm/6 text-gray-800 dark:text-gray-400 sm:col-span-2 sm:mt-0">{{ currentBag?.locationName }}
      </dd>
    </div>

    <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
      <dt class="text-sm/6 font-medium">Number of packages</dt>
      <dd class="mt-1 text-sm/6 text-gray-800 dark:text-gray-400 sm:col-span-2 sm:mt-0">
        {{ currentBag?.packages.length }}</dd>
    </div>

    <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
      <dt class="text-sm/6 font-medium">Total Weight</dt>
      <dd class="mt-1 text-sm/6 text-gray-800 dark:text-gray-400 sm:col-span-2 sm:mt-0">
        {{ formatInt(currentBag?.totalBagWeight) }} kg</dd>
    </div>

  </dl>
</template>

<script setup>

import { useLogic } from '../../composables/useLogic.js'

const { formatInt } = useLogic()

const props = defineProps({
  currentBag: Object,
})

</script>
