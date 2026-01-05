<template>
  <div
    class="w-full bg-white dark:bg-gray-800 border border-0 dark:border-1 rounded-md shadow-sm dark:shadow-none dark:border-gray-700/90">
    <div class="flex items-center justify-between py-6 px-8 border-b border-gray-200 dark:border-gray-700/90">
      <div>
        <h2>{{ title }}</h2>
        <p class="text-xs text-gray-400 mt-2">
          {{ packagesStackIsEmpty ? 'The pallet is empty, change it or stop to induct'
            : 'Pick a package in the pallet and drop it in the zone' }}
        </p>
      </div>
      <div class="flex items-center gap-2">
        <MinimalToggleMenu :items="menuItems" @select="(item) => handleMenuAction(item, actions)" />
      </div>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 max-sm:divide-y sm:divide-x divide-gray-200 dark:divide-gray-700/90">
      <div class="py-6 px-8">
        <p class="text-sm text-gray-400">{{ title }} id</p>
        <p class="text-4xl pt-2">{{ currentPallet.id }}</p>
      </div>
      <div class="py-6 px-8">
        <p class="text-sm text-gray-400">Nb of packages</p>
        <p class="text-4xl pt-2">{{ getNumberOfPackagesNotInducted(currentPallet) }}</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import MinimalToggleMenu from '../UI/MinimalToggleMenu.vue';

const props = defineProps({
  title: String,
  currentPallet: Object,
  currentPackage: Object,
  packagesStackIsEmpty: Boolean,
});

</script>
