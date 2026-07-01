<template>
  <InformationComponent :informations="palletInfos" />
</template>

<script setup>
import { computed } from 'vue';
import InformationComponent from '../UI/Modals/InformationComponent.vue';

const props = defineProps({
  currentRoadPart: Object,
})

const palletInfos = computed(() => {
  return {
    title: "Pallet informations",
    datas: [
      { 'Id': props.currentRoadPart?.id },
      { 'Truck': props.currentRoadPart?.truckName },
      { 'Number of packages': props.currentRoadPart?.packages.length },
    ]
  }
})

</script>
