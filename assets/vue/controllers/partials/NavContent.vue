<template>
  <div class="flex flex-col md:flex-row gap-4 text-sm md:flex-grow">
    <div v-for="item in navigations" :key="item.name">
      <a v-if="item.is_user" :href="item.href" :class="[item === currentItem ? 'bg-gray-50 dark:bg-gray-950 text-blue-700 dark:text-gray-300' : 'text-gray-700 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-blue-700 dark:hover:text-gray-300', 'rounded-md px-3 py-2 text-sm font-medium']"
        :aria-current="item.current ? 'page' : undefined">{{ item.name }}</a>
      <DarkTheme class="md:hidden ml-3" />
    </div>
  </div>
</template>

<script setup>

import { inject } from 'vue'
import DarkTheme from './DarkTheme.vue';

const { navigations, currentItem, is_user } = inject('navigation')

const props = defineProps({
  is_user: Boolean,
})

</script>
