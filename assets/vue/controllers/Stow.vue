<template>
  <div>

    <BorderedContent title="Floor">
      <FloorAisles :locations="locations" />
    </BorderedContent>

    <SidePanel ref="sidePanelRef" :title="currentPair ? currentPairPackages?.id : 'title'" width="md:w-5/6">

      <div v-if="currentPair" class="flex flex-col gap-8">
        <BorderedContent title="Package drop">
          <PackageDrop :pairPackages="currentPairPackages" />
        </BorderedContent>

        <BorderedContent title="Locations">
          <PairLocations :orderedLocations="currentPair.locations" />
        </BorderedContent>
      </div>

    </SidePanel>

  </div>
</template>

<script setup>

import { ref, provide, computed, watchEffect } from 'vue'

import { useFetch, usePostFetch } from '../composables/fetch.js'
import { useNotification } from '../composables/eventBus.js'

import BorderedContent from './UI/BorderedContent.vue'
import SidePanel from './UI/SidePanel.vue'
import PackageDrop from './StowComponents/PackageDrop.vue'
import FloorAisles from './StowComponents/FloorAisles.vue'
import PairLocations from './StowComponents/PairLocations.vue'

const { data: locations, error: errorDock } = useFetch('/getlocations')

const { notifier } = useNotification()

const stowingIsLoading = ref(false)

const currentPair = ref(null)
const currentPackage = ref(null)

const sidePanelRef = ref(null)

const currentPairPackages = computed(() => {
  const data = {
    "id": currentPair.value.id,
    "packages": currentPair.value.locations.flatMap(row =>
      row.packages.filter(p => p.userStow === null)
    )
  }
  return data
})

const setCurrentPair = (pair) => {
  currentPair.value = pair
  sidePanelRef.value?.toggleSidePanel()
}

const setCurrentPackage = (pack) => currentPackage.value = pack

const updateCurrentPairPackages = () => {
  const pkgId = currentPackage.value?.id
  if (!pkgId) return

  currentPair.value.locations = currentPair.value.locations.map(row => ({
    ...row,
    packages: row.packages.filter(p => p.id !== pkgId)
  }))

  if (!locations.value) return

  console.log('old loc', locations.value);

  locations.value = locations.value.map(aisle =>
    aisle.map(group => ({
      ...group,
      locations: group.locations.map(row => ({
        ...row,
        packages: (row.packages || []).filter(p => p.id !== pkgId),
      })),
    }))
  );
}

async function stowPackage(loc) {

  stowingIsLoading.value = true;

  if (!currentPackage.value) {
    stowingIsLoading.value = false;
    return
  }

  if (loc.name !== currentPackage.value.location.name) {
    currentPackage.value = false;
    stowingIsLoading.value = false;
    notifier('error', 'Stow', 'Wrong bag')
    return
  }

  const { data, error } = await usePostFetch(`/setUserStow/${currentPackage.value.id}`)

  if (data.value) {
    currentPackage.value = data.value
    updateCurrentPairPackages()
    setTimeout(() => {
      notifier('success', 'Stow', `The package (Id: ${currentPackage.value.id}) is stowed`)
    }, 1000);
    setTimeout(() => {
      stowingIsLoading.value = false;
      currentPackage.value = null
    }, 1500);
  }
}

provide('stow', { setCurrentPair, currentPair, setCurrentPackage, currentPackage, stowPackage, stowingIsLoading })

const handleToggle = () => {

  if (!sidePanelRef.value?.isOpen) {
    currentPackage.value = null
  }
}

watchEffect(handleToggle)

</script>
