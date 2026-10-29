<template>
  <div class="flex flex-col">

    <StatsHeader title="Postcodes group" notice="Choose group for each postcode" actionTitle="Choose"
      @actionClick="sidePanelRef?.toggleSidePanel()" />

    <SidePanel ref="sidePanelRef" title="Choose group" :synchronized=synchronized width="md:w-5/6">

      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 gap-4 mb-4">

        <div v-for="group in allGroupPostcodes" :key=group.id @dragover="onDragOver" @drop="onDrop($event, group.id)"
          class="drop-container w-full bg-white dark:bg-gray-800/50 border border-0 dark:border-1 rounded-md shadow-sm dark:shadow-none dark:border-gray-700/90">
          <div class="border-b border-gray-300 dark:border-gray-700/90 p-4">
            <div class="flex justify-between">
              <h3 class="text-sm font-semibold">{{ group?.name || 'Group name' }}</h3>
              <div>
                <BaseButton :title="group?.aisle || 'Aisle name'" styleColor="empty" size="sm"
                  :isDisabled="!group.aisle" @click="SelectOptionRef?.openDialog()" />
              </div>

            </div>
          </div>

          <div class="w-full grid grid-cols-3 gap-1 p-2 text-gray-800 dark:text-gray-400">
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


    <DialogComponentSlot ref="SelectOptionRef" :light="true">

      <SearchComponent :items="filteredAllAislesItems" v-model="searchQuery" @click="val => setAisle(val)" />

    </DialogComponentSlot>

  </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import { useDragStore } from '../../composables/useDragStore.js';
import { useNotification } from '../../composables/eventBus.js'

import SidePanel from '../UI/SidePanel.vue';
import StatsHeader from './StatsHeader.vue';

import { useFetch, usePostFetch } from '../../composables/fetch.js'
import BaseButton from '../UI/Buttons/BaseButton.vue';
import DialogComponentSlot from '../UI/Modals/DialogComponentSlot.vue';
import SearchComponent from '../UI/Modals/SearchComponent.vue';

const { notifier } = useNotification()
const { setDraggedItem, getDraggedItem } = useDragStore();

const { data: allGroupPostcodes, error: errorGroupPostcodes } = useFetch('/getgrouppostcodes')

const { data: allAisles, error: errorAisles } = useFetch('/getaisles')

const SelectOptionRef = ref(null)

const searchQuery = ref('')

const sidePanelRef = ref(null)

const synchronized = ref(true)

/* Aisle selection */

// TODO: Rendre l'overlay opaque à l'ouverture du dialog

const allAislesItems = computed(() => {
  return allAisles.value
    ? allAisles.value
      .filter(aisle => !aisle.hasGroupPostcodes && aisle.enabled)
      .map(aisle => ({
        label: aisle.name,
        color: aisle.enabled ? 'bg-green-500' : 'bg-gray-500',
        ...aisle
      }))
    : []
})

const filteredAllAislesItems = computed(() => {
  const query = searchQuery.value.trim().toLowerCase()

  if (!query) {
    return allAislesItems.value
  }

  return allAislesItems.value.filter(aisle => {
    return (
      aisle.label?.toLowerCase().includes(query) ||
      aisle.name?.toLowerCase().includes(query)
    )
  })
})

const setAisle = (aisle) => {
  if (!aisle) {
    return
  }
  console.log('Selected Aisle:', aisle);
  SelectOptionRef.value?.closeDialog()
}

/* Drag and Drop */

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

const movePostcodeToGroup = async (postcodeId, targetGroupId) => {
  const groups = allGroupPostcodes.value;
  if (!Array.isArray(groups)) return;

  const sourceGroup = groups.find(group =>
    group.postcodes?.some(postcode => postcode.id === postcodeId)
  );

  const targetGroup = groups.find(group => group.id === targetGroupId);

  if (!sourceGroup || !targetGroup || sourceGroup.id === targetGroup.id) return;

  const originalIndex = sourceGroup.postcodes.findIndex(
    postcode => postcode.id === postcodeId
  );

  if (originalIndex === -1) return;

  const [postcode] = sourceGroup.postcodes.splice(originalIndex, 1);
  targetGroup.postcodes.push(postcode);

  try {
    const { data, error } = await movePostCode(postcode, targetGroup);

    console.log('Result of movePostCode:', data.value);

    if (error) {
      throw new Error(error.value);
    }
  } catch (error) {
    const targetIndex = targetGroup.postcodes.findIndex(
      item => item.id === postcode.id
    );

    if (targetIndex !== -1) {
      targetGroup.postcodes.splice(targetIndex, 1);
    }

    sourceGroup.postcodes.splice(originalIndex, 0, postcode);

    console.error('Rollback du déplacement du postcode :', error);
  }
};

const movePostCode = async (postCodes, group) => {

  synchronized.value = false

  const { data, error } = await usePostFetch(`/movePostCode/${postCodes.id}`, { id: group.id })

  if (error.value) {
    setTimeout(() => {
      notifier('error', 'Postcodes  ', `${error.value}`)
      synchronized.value = true
      return { error }
    }, 1000);
  }

  if (data.value) {
    console.log(data.value)
    synchronized.value = true
    return { data };
  }
}

watch(
  () => allGroupPostcodes.value,
  (val) => {
    console.log('allGroupPostcodes watch:', val);
  },
  { immediate: true, deep: true }
);

watch(
  () => allAisles.value,
  (val) => {
    console.log('allAisles watch:', val);
  },
  { immediate: true, deep: true }
);

</script>

<style scoped>
html.grabbing * {
  cursor: grabbing !important;
}
</style>
