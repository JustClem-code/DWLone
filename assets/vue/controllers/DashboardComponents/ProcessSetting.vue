<template>
  <div class="flex flex-col">

    <StatsHeader title="Postcodes group" notice="Choose group for each postcode" actionTitle="Choose"
      @actionClick="sidePanelRef?.toggleSidePanel()" />

    <SidePanel ref="sidePanelRef" title="Choose group" width="md:w-5/6">

      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 gap-4 mb-4">

        <div v-for="group in allGroupPostcodes" :key=group.id
          class="w-full bg-white dark:bg-gray-800/50 border border-0 dark:border-1 rounded-md shadow-sm dark:shadow-none dark:border-gray-700/90">
          <div class="border-b border-gray-300 dark:border-gray-700/90 p-4">
            <div class="flex justify-between">
              <h3 class="text-sm font-semibold">{{ group?.name || 'Group name' }}</h3>
            </div>
          </div>

          <div class="drop-container w-full grid grid-cols-3 gap-1 p-2 text-gray-800 dark:text-gray-400"
            @dragover="onDragOver" @drop="onDrop($event, group.id)">
            <div v-for="postcode in group?.postcodes" :key=postcode.id>

              <transition name="fade-slide" tag="div" enter-active-class="transition-all duration-500 ease-out"
                enter-from-class="opacity-0" enter-to-class="opacity-100">

                <span draggable="true" @dragstart="(e) => onDragStart(e, postcode.id)" @dragend="onDragEnd()"
                  :class="cursorType"
                  class="w-full inline-flex justify-center items-center rounded-md font-medium inset-ring bg-gray-400/10 text-gray-400 inset-ring-gray-500/20 px-4 py-2">
                  {{ postcode.name }}
                </span>

              </transition>

            </div>
          </div>

        </div>

      </div>
    </SidePanel>



  </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import { useDragStore } from '../../composables/useDragStore.js';

import SidePanel from '../UI/SidePanel.vue';
import StatsHeader from './StatsHeader.vue';

import { useFetch, usePostFetch } from '../../composables/fetch.js'

const { setDraggedItem, getDraggedItem } = useDragStore();

const { data: allGroupPostcodes, error: errorGroupPostcodes } = useFetch('/getgrouppostcodes')

const sidePanelRef = ref(null)

const isDragging = ref(false)

const cursorType = computed(() => {
  return isDragging.value ? 'cursor-grabbing' : 'cursor-grab'
})

const onDragStart = (event, postcodeId) => {
  isDragging.value = true;
  document.documentElement.classList.add("grabbing");
  setDraggedItem(postcodeId);
  event.dataTransfer.effectAllowed = 'move';
};

const onDragOver = (event) => {
  event.preventDefault();
  event.dataTransfer.dropEffect = 'move';
};

const onDrop = (event, targetGroupId) => {
  event.preventDefault();

  const postcodeId = getDraggedItem();
  if (!postcodeId) return;

  movePostcodeToGroup(postcodeId, targetGroupId);
};

const onDragEnd = () => {
  isDragging.value = false;
  document.documentElement.classList.remove("grabbing");
};

const movePostcodeToGroup = (postcodeId, targetGroupId) => {
  const groups = allGroupPostcodes.value;
  if (!Array.isArray(groups)) return;

  const sourceGroup = groups.find(group =>
    group.postcodes?.some(postcode => postcode.id === postcodeId)
  );

  const targetGroup = groups.find(group => group.id === targetGroupId);

  if (!sourceGroup || !targetGroup || sourceGroup.id === targetGroup.id) return;

  const index = sourceGroup.postcodes.findIndex(
    postcode => postcode.id === postcodeId
  );

  if (index === -1) return;

  const [postcode] = sourceGroup.postcodes.splice(index, 1);
  targetGroup.postcodes.push(postcode);
};

watch(
  () => allGroupPostcodes.value,
  (val) => {
    console.log('allGroupPostcodes watch:', val);
  },
  { immediate: true, deep: true }
);

</script>

<style scoped>
html.grabbing * {
  cursor: grabbing !important;
}
</style>
