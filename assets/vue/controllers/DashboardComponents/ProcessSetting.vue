<template>
  <div class="flex flex-col">

    <StatsHeader title="Postcodes group" notice="Choose group for each postcode" actionTitle="Choose"
      @actionClick="sidePanelRef?.toggleSidePanel()" />

    <SidePanel ref="sidePanelRef" title="Choose group" width="md:w-5/6">

      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 gap-4">

        <div v-for="group in allGroupPostcodes" :key=group.id
          class="w-full bg-white dark:bg-gray-800/50 border border-0 dark:border-1 rounded-md shadow-sm dark:shadow-none dark:border-gray-700/90">
          <div class="border-b border-gray-300 dark:border-gray-700/90 p-4">
            <div class="flex justify-between">
              <h3 class="text-sm font-semibold">{{ group?.name || 'Group name' }}</h3>
            </div>
          </div>

          <div class="w-full grid grid-cols-3 gap-1 p-2 text-gray-800 dark:text-gray-400">
            <div v-for="postcode in group?.postcodes" :key=postcode.id>
              <span
                class="w-full inline-flex justify-center items-center rounded-md font-medium inset-ring bg-gray-400/10 text-gray-400 inset-ring-gray-500/20 px-4 py-2">
                {{ postcode.name }}
              </span>
            </div>
          </div>

        </div>

      </div>
    </SidePanel>



  </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue';

import SidePanel from '../UI/SidePanel.vue';
import StatsHeader from './StatsHeader.vue';

import { useFetch, usePostFetch } from '../../composables/fetch.js'

const { data: allGroupPostcodes, error: errorGroupPostcodes } = useFetch('/getgrouppostcodes')

const sidePanelRef = ref(null)

watch(
  () => allGroupPostcodes.value,
  (val) => {
    console.log('allGroupPostcodes watch:', val);
  },
  { immediate: true, deep: true }
);

</script>
