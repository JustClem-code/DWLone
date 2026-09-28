<template>
  <div class="flex flex-col gap-8">

    <StatsHeader title="Picking processing" notice="You can see the picking overview" actionTitle="Action on picking"
      @actionClick="sidePanelRef?.toggleSidePanel()" :statistics="pickingStats" />

    <RoadPartsList v-if="allRoadParts" :roadParts="allRoadParts" />
    <div v-else-if="errorGetAllRoadParts">Error: {{ errorGetAllRoadParts }}</div>
    <div v-else>Loading...</div>

    <SidePanel ref="sidePanelRef" title="Action on picking">
      <div class="flex flex-col gap-2 mb-8">

        <RadioForm :options="automaticOptions" v-model="selected" :isLoading="globalLoading"
          @submitForm="submitAutomaticForm" :sidePanelRef="sidePanelRef" />

      </div>

    </SidePanel>

  </div>
</template>

<script setup>
import { ref, computed, provide, watch, watchEffect } from 'vue';

import { useFetch, usePostFetch } from '../../composables/fetch.js'
import { useNotification } from '../../composables/eventBus.js'

import SidePanel from '../UI/SidePanel.vue';
import StatsHeader from './StatsHeader.vue';
import RoadPartsList from './RoadPartsList.vue';
import RadioForm from '../UI/RadioForm.vue';

const { notifier } = useNotification()

const { data: allRoadParts, error: errorGetAllRoadParts } = useFetch('/getAllRoadParts')

const selected = ref(null)

const sidePanelRef = ref(null)

const globalLoading = ref(null)

const allRoadPartsNumber = computed(() => {
  return allRoadParts.value ? allRoadParts.value.length : 0
})

const allRoadPartsWithUser = computed(() => {
  return allRoadParts.value ? allRoadParts.value.filter(r => r.userName).length : 0
})

const allRoadPartsWithoutUser = computed(() => {
  return allRoadParts.value ? allRoadParts.value.filter(r => !r.userName).length : 0
})

const allRoadPartsStagged = computed(() => {
  return allRoadParts.value ? allRoadParts.value.filter(r => r.stagged).length : 0
})

const pickingStats = computed(() => [
  { 'title': 'Number of roads', 'number': `${allRoadPartsNumber.value}` },
  { 'title': 'In progress', 'number': `${allRoadPartsWithUser.value}` },
  { 'title': 'Picking progress', 'number': `0` },
])


const automaticOptions = computed(() => [
  { 'value': 'Sequencing', 'notice': 'Generate roads', 'number': '', 'disabled': allRoadPartsNumber.value > 0 },
  { 'value': 'Delete', 'notice': 'Delete all road parts', 'number': `${allRoadPartsNumber.value}`, 'disabled': allRoadPartsNumber.value === 0 },
  { 'value': 'Automatic picking', 'notice': 'Automatically pick all road parts', 'number': `${allRoadPartsWithoutUser.value}`, 'disabled': allRoadPartsWithoutUser.value === 0 },
  { 'value': 'Hard reset', 'notice': 'Reset all road parts', 'number': `${allRoadPartsWithUser.value}`, 'disabled': allRoadPartsWithUser.value === 0 },
])

const submitAutomaticForm = () => {
  const actions = {
    'Sequencing': () => generateAllRoads(),
    'Delete': () => deleteAllRoads(),
    'Automatic picking': () => automatingPicking(),
    'Hard reset': () => hardResetPicking(),
  }

  const run = actions[selected.value]

  if (!run) {
    console.log('error');
    return;
  }

  run();
}

const generateAllRoads = async () => {
  globalLoading.value = true;

  const { data, error } = await usePostFetch(`/generateAllRoads`)

  if (error.value) {
    setTimeout(() => {
      notifier('error', 'Picking', `Error generating roads`)
      globalLoading.value = false;
    }, 1000);
  }

  if (data.value) {
    setTimeout(() => {
      globalLoading.value = false;
    }, 500);
    setTimeout(() => {
      allRoadParts.value = data.value
      notifier('success', 'Picking', `${allRoadPartsNumber.value} generated`)
      sidePanelRef.value?.toggleSidePanel()
    }, 1000);
  }
}

const deleteAllRoads = async () => {
  globalLoading.value = true;

  const { data, error } = await usePostFetch(`/deleteAllRoads`)

  if (error.value) {
    setTimeout(() => {
      notifier('error', 'Picking', `Error deleting roads`)
      globalLoading.value = false;
    }, 1000);
  }

  if (data.value) {
    setTimeout(() => {
      globalLoading.value = false;
    }, 500);
    setTimeout(() => {
      allRoadParts.value = data.value
      notifier('success', 'Picking', `All roads deleted`)
      sidePanelRef.value?.toggleSidePanel()
    }, 1000);
  }
}

const automatingPicking = async () => {

  globalLoading.value = true

  const { data, error } = await usePostFetch('/automatingpicking')

  if (error.value) {
    notifier('error', 'Automatic picking', 'An error occurred')
    return
  }

  if (data.value) {
    allRoadParts.value = data.value
    notifier('success', 'Automatic picking', 'Picking is finished!!!')
    globalLoading.value = false
    sidePanelRef.value?.toggleSidePanel()
  }
}

const hardResetPicking = async () => {
  globalLoading.value = true;

  const { data, error } = await usePostFetch(`/hardResetPicking`)

  if (error.value) {
    setTimeout(() => {
      notifier('error', 'Picking', `Error hard reseting`)
      globalLoading.value = false;
    }, 1000);
  }

  if (data.value) {
    setTimeout(() => {
      globalLoading.value = false;
    }, 500);
    setTimeout(() => {
      allRoadParts.value = data.value
      notifier('success', 'Picking', `All roads reseted`)
      sidePanelRef.value?.toggleSidePanel()
    }, 1000);
  }
}

const updateRoadParts = (roadPart) => {
  const index = allRoadParts.value.findIndex(item => item.id === roadPart.id);

  if (index !== -1) {
    allRoadParts.value.splice(index, 1, roadPart);
  }

}

const resetRoadpart = async (roadPart) => {
  globalLoading.value = true;

  const { data, error } = await usePostFetch(`/resetRoadPart/${roadPart.id}`)

  if (error.value) {
    setTimeout(() => {
      notifier('error', 'Picking  ', `${error.value}`)
      globalLoading.value = false;
    }, 1000);
  }

  if (data.value) {
    setTimeout(() => {
      globalLoading.value = false;
    }, 500);
    setTimeout(() => {
      updateRoadParts(data.value)
      notifier('success', 'Picking', `The roadpart (Id: ${roadPart.id}) is reseted`)
    }, 1000);
  }
}

provide('pickingProcessing', { resetRoadpart, globalLoading })

watch(
  () => allRoadParts,
  (val) => {
    console.log('allRoadParts watch:', val);
  },
  { immediate: true, deep: true }
);

</script>
