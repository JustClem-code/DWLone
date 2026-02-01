<template>
  <div>

    <BorderedContent v-if="!currentPair" title="Floor">
      <FloorAisles :locations="locations" />
    </BorderedContent>

    <div v-else class="flex flex-col gap-8">
      <BorderedContent title="Package drop">
        <PackageDrop :pairPackages="currentPairPackages" />
      </BorderedContent>

      <BorderedContent title="Locations">
        <PairLocations :orderedLocations="orderedLocations" />
      </BorderedContent>
    </div>

  </div>
</template>

<script setup>

import { onMounted, watch, ref, provide, computed } from 'vue'

import { useFetch, usePostFetch } from '../composables/fetch.js'
import { useNotification } from '../composables/eventBus.js'

import BorderedContent from './UI/BorderedContent.vue'
import PackageDrop from './StowComponents/PackageDrop.vue'
import FloorAisles from './StowComponents/FloorAisles.vue'
import PairLocations from './StowComponents/PairLocations.vue'

const { data: locations, error: errorDock } = useFetch('/getlocations')

const { notifier } = useNotification()

const stowingIsLoading = ref(false)

const STORAGE_KEY = 'currentPair'

const currentPair = ref(null)
const currentPackage = ref(null)

onMounted(() => {
  const raw = localStorage.getItem(STORAGE_KEY)
  currentPair.value = raw ? JSON.parse(raw) : null
})

const firstAisleNumber = computed(() => {
  const match = currentPair.value.locations[0].name.match(/\d+/);
  return match ? parseInt(match[0], 10) : null;
})

const pairFirstLetter = computed(() => {
  return `${currentPair.value.locations[0].name[0]}`
})

const orderedLocations = computed(() => {
  const orderSpecs = [
    { floor: `${firstAisleNumber.value}`, side: '2' }, // col 1
    { floor: `${firstAisleNumber.value}`, side: '1' }, // col 2
    { floor: `${firstAisleNumber.value + 1}`, side: '1' }, // col 3
    { floor: `${firstAisleNumber.value + 1}`, side: '2' }, // col 4
  ]

  const letters = ['A', 'B', 'C', 'D', 'E', 'G']

  const byKey = new Map(
    currentPair.value.locations.map(loc => [loc.name, loc])
  )

  const result = []

  for (const spec of orderSpecs) {
    for (const letter of letters) {
      const key = `${pairFirstLetter.value}-${spec.floor}-${letter}-${spec.side}`
      const loc = byKey.get(key)
      if (loc) result.push(loc)
    }
  }

  return result
})

const currentPairPackages = computed(() => {
  const data = {
    "id": currentPair.value.id,
    "packages": currentPair.value.locations.flatMap(row =>
      row.packages.filter(p => p.userStow === null)
    )
  }
  return data
})

const setCurrentPair = (pair) => currentPair.value = pair

const setCurrentPackage = (pack) => currentPackage.value = pack

const updateCurrentPairPackages = () => {
  const pkgId = currentPackage.value?.id
  if (!pkgId) return

  currentPair.value.locations = currentPair.value.locations.map(row => ({
    ...row,
    packages: row.packages.filter(p => p.id !== pkgId)
  }))

  if (!locations.value) return

  locations.value = locations.value.map(group => ({
    ...group,
    locations: group.locations.map(row => ({
      ...row,
      packages: (row.packages || []).filter(p => p.id !== pkgId),
    })),
  }));
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

watch(
  currentPair,
  (val) => {
    if (val === null) {
      localStorage.removeItem(STORAGE_KEY)
    } else {
      localStorage.setItem(STORAGE_KEY, JSON.stringify(val))
    }
  },
  { deep: true }
)

</script>
