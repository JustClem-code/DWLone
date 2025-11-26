<template>
  <div class="flex flex-col gap-8">
    <BorderedContent title="Docks">
      <div v-if="docks" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
        <InboundDockCard v-for="dock in docks" :key=dock.id :dock="dock" />
      </div>
      <div v-else-if="errorDock">Error: {{ errorDock }}</div>
      <div v-else class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
        <InboundDockCard v-for="i in 12" />
      </div>
    </BorderedContent>
  </div>
</template>

<script setup>

import { ref, provide, computed } from 'vue'
import BorderedContent from './UI/BorderedContent.vue'
import InboundDockCard from './UnloadingComponents.vue/InboundDockCard.vue'

import { useFetch, usePostFetch } from '../composables/fetch.js'
import emitter from '../composables/eventBus.js'

const notifier = (type, message, message_2) => {
  emitter.emit('notify', { type: type, message: message, message_2: message_2 })
}

const { data: docks, error: errorDock } = useFetch('/getoccupieddocks')

const unLoadingData = ref(null)
const unLoadingError = ref(null)
const unLoadingIsLoading = ref(false)

const notUnloadedPallets = computed(() => {
  if (!docks.value) return
  return docks.value.filter(dock => dock.truckId !== null);
})

provide('unLoading', { notUnloadedPallets, unloadingPallet, unLoadingIsLoading })

console.log("occupied docks", docks);


const updateListElements = () => {
  const {
    dockId,
    palletId,
    userId,
    userName
  } = unLoadingData.value

  const dock = docks.value.find(d => d.id === dockId)

  if (!dock) return

  const pallet = dock.pallets.find(p => p.id === palletId)

  pallet.userId = userId || null
  pallet.userName = userName || null

}

async function unloadingPallet(palletId, dockId, reset) {
  unLoadingIsLoading.value = true;

  const { data, error } = await usePostFetch(`/unloadingPallet/${palletId.id}`, { id: dockId?.id ?? null, reset: reset ?? false })

  unLoadingData.value = data.value;
  unLoadingError.value = error.value;

  if (unLoadingData.value) {
    updateListElements()
    unLoadingIsLoading.value = false;

    console.log(unLoadingData.value);
    if (reset) {
      notifier('success', 'Reset', `The pallet (Id: ${unLoadingData.value.palletId}) is reset`)
    } else {
      notifier('success', 'Unloading', `The pallet (Id: ${unLoadingData.value.palletId}) is undloaded`)
    }

  }
}


</script>
