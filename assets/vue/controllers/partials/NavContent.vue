<template>
  <div class="flex flex-col md:flex-row gap-4 text-sm md:flex-grow">
    <a v-for="item in navigations" :key="item.name" :href="item.href"
      :class="[item === currentItem ? 'bg-gray-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50 hover:text-blue-700', 'rounded-md px-3 py-2 text-sm font-medium']"
      :aria-current="item.current ? 'page' : undefined">{{ item.name }}</a>
  </div>
</template>

<script setup>

import { inject } from 'vue'

const { navigations, currentItem } = inject('navigation')

</script>
