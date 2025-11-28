<template>
  <h3 class="pb-6 border-b border-gray-200 dark:border-gray-700/90">Pallet informations</h3>
  <dl class="divide-y divide-gray-200 dark:divide-gray-700/90">
    <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
      <dt class="text-sm/6 font-medium">Id</dt>
      <dd class="mt-1 text-sm/6 text-gray-800 dark:text-gray-400 sm:col-span-2 sm:mt-0">{{ currentPallet?.id }}</dd>
    </div>
    <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
      <dt class="text-sm/6 font-medium">Dock</dt>
      <dd class="mt-1 text-sm/6 text-gray-800 dark:text-gray-400 sm:col-span-2 sm:mt-0">{{ currentPallet?.userId ? 'Unloaded' : 'Not unloaded' }}</dd>
    </div>
    <!-- <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
      <dt class="text-sm/6 font-medium">Number of pallets</dt>
      <dd class="mt-1 text-sm/6 text-gray-800 dark:text-gray-400 sm:col-span-2 sm:mt-0">
        {{ currentPallet?.pallets.length }}</dd>
    </div> -->
  </dl>
</template>

<script setup>

const props = defineProps({
  currentPallet: Object,
})

</script>
