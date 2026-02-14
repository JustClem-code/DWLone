<template>
  <div>
    <h3 class="pb-6 border-b border-gray-200 dark:border-gray-700/90">{{ informations.title }}</h3>
    <dl class="divide-y divide-gray-200 dark:divide-gray-700/90">

      <div v-for="(value, key) in flattenDatas" :key="key">
        <div v-if="key !== 'Timeline'" class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
          <dt class="text-sm/6 font-medium">{{ key }}</dt>
          <dd class="mt-1 text-sm/6 text-gray-800 dark:text-gray-400 sm:col-span-2 sm:mt-0">{{ value }}</dd>
        </div>

        <div v-if="key === 'Timeline'" class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
          <dt class="text-sm/6 font-medium">{{ key }}</dt>
          <dd class="mt-2 text-sm sm:col-span-2 sm:mt-0">
            <ul>
              <li v-for="date in value" :key="date.title" class="relative flex gap-1 items-center mb-6 last:mb-0">
                <div class="timeline_border absolute top-0 left-0 -bottom-6 flex w-6 justify-center">
                  <div class="w-[1px] bg-gray-300 dark:bg-gray-700/90 h-full"></div>
                </div>
                <div class="relative flex size-6 flex-none items-center justify-center bg-white dark:bg-gray-800">
                  <div
                    class="size-2 rounded-full bg-gray-200/50 dark:bg-gray-700/50 inset-ring inset-ring-gray-300 dark:inset-ring-gray-700/90">
                  </div>
                </div>
                <p class="text-xs py-1 text-nowrap">{{ date.title }}
                  <span class="text-gray-800 dark:text-gray-400">&nbsp;{{ date.date }}</span>
                  <span v-if="date.user" class="text-gray-800 dark:text-gray-400">&nbsp;{{ date.user }}</span>
                </p>
              </li>
            </ul>
          </dd>
        </div>
      </div>

    </dl>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  informations: Object,
})

const flattenDatas = computed(() => {
  const flat = {}
  props.informations.datas.forEach(item => {
    const [key, value] = Object.entries(item)[0]
    flat[key] = value
  })
  return Object.fromEntries(
    Object.entries(flat).filter(([key, value]) => value != null)
  );
})

</script>


<style scoped>
li:last-child .timeline_border>div {
  display: none;
}
</style>
