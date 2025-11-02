<template>
  <h3 class="pb-6 border-b border-gray-200 dark:border-gray-700/90">Truck informations</h3>
  <dl class="divide-y divide-gray-200 dark:divide-gray-700/90">
    <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
      <dt class="text-sm/6 font-medium">Vrid</dt>
      <dd class="mt-1 text-sm/6 text-gray-800 dark:text-gray-400 sm:col-span-2 sm:mt-0">{{ currentTruck?.name }}</dd>
    </div>
    <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
      <dt class="text-sm/6 font-medium">Dock</dt>
      <dd class="mt-1 text-sm/6 text-gray-800 dark:text-gray-400 sm:col-span-2 sm:mt-0">{{ currentTruck?.dock }}</dd>
    </div>
    <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
      <dt class="text-sm/6 font-medium">Number of pallets</dt>
      <dd class="mt-1 text-sm/6 text-gray-800 dark:text-gray-400 sm:col-span-2 sm:mt-0">{{ currentTruck?.pallets.length }}</dd>
    </div>
    <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
      <dt class="text-sm/6 font-medium">Timeline</dt>
      <dd class="mt-2 text-sm sm:col-span-2 sm:mt-0">
        <ul v-if="currentTruck">
          <li v-for="date in currentTruckDates" class="relative flex gap-1 items-center mb-6 last:mb-0">
            <div class="timeline_border absolute top-0 left-0 -bottom-6 flex w-6 justify-center">
              <div class="w-[1px] bg-gray-700/90 h-full"></div>
            </div>
            <div class="relative flex size-6 flex-none items-center justify-center bg-gray-800">
              <div class="size-2 rounded-full bg-gray-700/50 inset-ring inset-ring-gray-700/90"></div>
            </div>
            <p class="text-xs py-1">{{ date.title }}<span class="text-gray-800 dark:text-gray-400">&nbsp;{{ date.date
                }}</span></p>
          </li>
        </ul>
      </dd>
    </div>
  </dl>
</template>

<script setup>
import { computed } from 'vue'
import { formattedDateFr } from '../../composables/dateFormat.js'

const props = defineProps({
  currentTruck: Object,
})

const currentTruckDates = computed(() => [
  { title: 'Expected on', date: formattedDateFr(props.currentTruck?.expectedDate) ?? 'No date' },
  { title: 'Delivered on', date: formattedDateFr(props.currentTruck?.deliveryDate) ?? 'No date' },
  { title: 'Departure on', date: formattedDateFr(props.currentTruck?.departureDate) ?? 'No date' },
])
</script>

<style scoped>
li:last-child .timeline_border>div {
  display: none;
}
</style>
