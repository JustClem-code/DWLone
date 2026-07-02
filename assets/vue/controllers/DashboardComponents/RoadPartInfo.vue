<template>
  <InformationComponent :informations="roadPartInfos" />
</template>

<script setup>
import { computed } from 'vue';
import InformationComponent from '../UI/Modals/InformationComponent.vue';
import { formattedDateFr } from '../../composables/dateFormat.js'

const props = defineProps({
  currentRoadPart: Object,
})

const roadPartInfos = computed(() => {
  return {
    title: "Road part informations",
    datas: [
      { 'Id': props.currentRoadPart?.id ?? 'No id' },
      { 'Name': `${props.currentRoadPart?.road} # ${props.currentRoadPart?.number}` },
      { 'User name': props.currentRoadPart?.userName ?? 'no user' },
      { 'Number of bags': props.currentRoadPart?.bags.length ?? '0' },
      { 'picking started at': formattedDateFr(props.currentRoadPart?.pickingStartedAt) ?? '0' },
      { 'picking duration seconds': props.currentRoadPart?.pickingDurationSeconds ?? '0' },
    ]
  }
})

</script>
