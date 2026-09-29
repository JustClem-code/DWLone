<template>
  <div class="flex flex-col gap-8">

    <StatsHeader title="Bags processing" notice="You can see bags overview" actionTitle="Zoom on bags"
      @actionClick="sidePanelRef?.toggleSidePanel()" :statistics="bagsStats" />

    <SidePanel ref="sidePanelRef" title="Zoom on bags" width="md:w-5/6">

  

    </SidePanel>

 

  </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue';

import SidePanel from '../UI/SidePanel.vue';
import StatsHeader from './StatsHeader.vue';

const sidePanelRef = ref(null)

</script>
