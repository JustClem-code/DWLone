<template>
  <div
    class="w-full bg-white dark:bg-gray-800 border border-0 dark:border-1 rounded-md shadow-sm dark:shadow-none dark:border-gray-700/90">

    <div class="flex items-center justify-between py-6 px-8 border-b border-gray-200 dark:border-gray-700/90">
      <div>
        <h2>{{ title }}</h2>
        <p class="text-xs text-gray-400 mt-2">
          {{ notice }}
        </p>
      </div>
      <div class="flex items-center gap-2">
        <BaseButton :title="actionTitle" @click="emit('actionClick')" styleColor="empty" />
      </div>
    </div>

    <div v-if="statistics"
      class="grid grid-cols-1 sm:grid-cols-3 max-sm:divide-y sm:divide-x divide-gray-200 dark:divide-gray-700/90">
      <div v-for="stat in statistics" :key="stat.title" class="py-6 px-8">
        <p class="text-sm text-gray-400">{{ stat.title }}</p>
        <p class="text-4xl pt-2">{{ stat.number }}</p>
      </div>
    </div>

  </div>
</template>

<script setup>
import BaseButton from '../UI/Buttons/BaseButton.vue';

const props = defineProps({
  title: String,
  notice: String,
  actionTitle: String,
  statistics: Array,
});

const emit = defineEmits(['actionClick'])

</script>
