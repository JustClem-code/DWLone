<template>
  <InformationComponent :informations="palletInfos" />
</template>

<script setup>
import { computed } from 'vue';
import InformationComponent from '../UI/Modals/InformationComponent.vue';

const props = defineProps({
  currentPallet: Object,
})

const palletInfos = computed(() => {
  return {
    title: "Pallet informations",
    datas: [
      { 'Id': props.currentPallet?.id },
      { 'Truck': props.currentPallet?.truckName },
      { 'Number of packages': props.currentPallet?.packages.length },
    ]
  }
})

</script>
