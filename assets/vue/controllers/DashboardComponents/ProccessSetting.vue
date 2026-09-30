<template>
  <div class="flex flex-col">

    <StatsHeader title="Postcodes group" notice="Choose group for each postcode" actionTitle="Choose group"
      @actionClick="sidePanelRef?.toggleSidePanel()" />

    <SidePanel ref="sidePanelRef" title="Choose group" width="md:w-5/6">

  

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
