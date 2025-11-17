<template>
  <div class="flex flex-col md:flex-row justify-between w-full">
    <div class="flex flex-col md:flex-row md:items-center text-sm md:flex-grow">
      <div v-for="item in navigations" :key="item.name" class="mb-4 md:mb-0 md:mr-4">
        <a v-if="item.show" :href="item.href" :class="navStyle(item)"
          :aria-current="item.current ? 'page' : undefined">{{ item.name }}</a>
      </div>
    </div>
    <div class="flex flex-col md:flex-row md:items-center">
      <div class="flex flex-col md:flex-row text-sm md:flex-grow">
        <div v-for="item in authNavigations" :key="item.name" class="mb-4 md:mb-0 md:mr-4">
          <a v-if="item.show" :href="item.href" :class="[navStyle(item), hasBorder(item)]"
            :aria-current="item.current ? 'page' : undefined">{{ item.name }}</a>
        </div>
      </div>
      <AvatarToggleMenu :items="avatarNavigations" />
      <DarkTheme class="ml-3 md:ml-0" />
    </div>
  </div>
</template>

<script setup>

import { inject } from 'vue'
import DarkTheme from './DarkTheme.vue';
import AvatarToggleMenu from './avatarToggleMenu.vue';

const { navigations, currentItem, authNavigations, avatarNavigations } = inject('navigation')

const navStyle = (item) => {
  return [item.name === currentItem.value.name ? 'bg-gray-50 dark:bg-gray-950 text-blue-700 dark:text-gray-300' : 'text-gray-700 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-blue-700 dark:hover:text-gray-300', 'rounded-md px-3 py-2 text-sm font-medium']
}

const hasBorder = (item) => {
  return item.name === 'Sign up' ? 'border' : ''
}

</script>
