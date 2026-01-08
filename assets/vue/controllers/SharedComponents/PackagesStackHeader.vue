<template>
  <div
    class="w-full bg-white dark:bg-gray-800 border border-0 dark:border-1 rounded-md shadow-sm dark:shadow-none dark:border-gray-700/90">
    <div class="flex items-center justify-between py-6 px-8 border-b border-gray-200 dark:border-gray-700/90">
      <div>
        <h2>{{ title ?? 'Packages' }}</h2>
        <p class="text-xs text-gray-400 mt-2">
          {{ notice ?? 'Notice' }}
        </p>
      </div>
      <div class="flex items-center gap-2">
        <BaseButton v-if="backButton" @click="emit('backClick')" title="Back" styleColor="empty"/>
        <MinimalToggleMenu v-if="menuItems" :items="menuItems" @select="(item) => handleMenuAction(item, actions)" />
      </div>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 max-sm:divide-y sm:divide-x divide-gray-200 dark:divide-gray-700/90">
      <div class="py-6 px-8">
        <p class="text-sm text-gray-400">{{ title }} id</p>
        <p class="text-4xl pt-2">{{ packagesStack.id }}</p>
      </div>
      <div class="py-6 px-8">
        <p class="text-sm text-gray-400">Nb of packages</p>
        <p class="text-4xl pt-2">{{ numberOfPackages }}</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import MinimalToggleMenu from '../UI/MinimalToggleMenu.vue';

import { useLogic } from '../../composables/useLogic.js';
import BaseButton from '../UI/Buttons/BaseButton.vue';

const props = defineProps({
  title: String,
  packagesStack: Object,
  notice: String,
  packagesStackIsEmpty: Boolean,
  backButton: Boolean,
  menuItems: Array,
  actions: Object,
  numberOfPackages: Number
});

const emit = defineEmits(['backClick'])

const { handleMenuAction } = useLogic()

</script>
