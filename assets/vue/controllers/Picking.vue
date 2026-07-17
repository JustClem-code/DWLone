<template>
  <div class="flex flex-col gap-8">
    <BorderedContent title="Road part">
      <DashedEmptyState v-if="!currentRoadPart" @click="setUserToRoadPart()" title="Get a road Part"
        :disabled="setUserToRoadPartIsLoading">
        <AddDatabaseIcon size="size-16" color="text-gray-200 dark:text-gray-700/90" />
        <AnimateSpin v-show="setUserToRoadPartIsLoading" class="absolute" />
      </DashedEmptyState>
      <RoadPartHeader v-else :title="currentRoadPartTitle" :notice="roadPartNotice" actionTitle="Automating steps"
        @actionClick="sidePanelRef?.toggleSidePanel()" :statistics="roadPartStats" />
    </BorderedContent>

    <BorderedContent v-if="currentRoadPart" title="Floor" class="flex flex-col gap-8">
      <FloorAisles v-if="currentRoadPart?.cart" :locations="locations" :currentBag="currentBag"
        @click="val => setCurrentPair(val)" />
      <FloorStaggingArea :staggingAreas="staggingAreas" />
    </BorderedContent>

    <SidePanel ref="sidePanelRef" :title="currentPair ? currentPair?.id : 'title'" width="md:w-5/6">

      <div v-if="currentPair" class="flex flex-col gap-8">
        <BorderedContent title="Cart">
          <BaseButton title="Scan cart" @click="pickingBag(currentRoadPart?.cart)" styleColor="empty"
            :isDisabled="currentBagPickedId === null" :isLoading="pickingBagIsLoading" />
        </BorderedContent>

        <BorderedContent title="Locations">
          <PairLocations :orderedLocations="currentPair.locations" :isDisabledButton="currentBagPickedId !== null"
            :currentLocName="currentBag?.location" :currentBagName="currentBag?.name"
            @click="val => scanBag(val.bag)" />
        </BorderedContent>
      </div>

    </SidePanel>

    <DialogComponentSlot ref="confirmFinishPickDialogRef">
      <ConfirmationComponent question="Are you sure to reset ?" @confirm="resetItem"
        @cancel="confirmFinishPickDialogRef?.closeDialog()" />
    </DialogComponentSlot>

  </div>
</template>

<script setup>

import { ref, provide, computed, watchEffect, watch, onMounted, onBeforeUnmount } from 'vue'

import { useFetch, usePostFetch } from '../composables/fetch.js'
import { useNotification } from '../composables/eventBus.js'
import { useTimer } from '../composables/useTimer.js'

import BorderedContent from './UI/BorderedContent.vue'
import SidePanel from './UI/SidePanel.vue'
import BaseButton from './UI/Buttons/BaseButton.vue'
import DashedEmptyState from './UI/DashedEmptyState.vue'
import AddDatabaseIcon from './UI/Icons/AddDatabaseIcon.vue'
import AnimateSpin from './UI/AnimateSpin.vue'
import DialogComponentSlot from './UI/Modals/DialogComponentSlot.vue'
import ConfirmationComponent from './UI/Modals/ConfirmationComponent.vue'

import FloorAisles from './SharedComponents/FloorAisles.vue'
import PairLocations from './SharedComponents/PairLocations.vue'
import FloorStaggingArea from './PickingComponents/FloorStaggingArea.vue'
import RoadPartHeader from './PickingComponents/RoadPartHeader.vue'

const { data: locations, error: errorLocations } = useFetch('/getLocationsLight')
const { data: staggingAreas, error: errorStaggingAreas } = useFetch('/getStaggingAreas')
const { data: currentRoadPart, error: errorCurrentRoadPart } = useFetch('/getCurrentUserRoadpart')

const { notifier } = useNotification()

const { isRunning, durationSeconds, format: timeToPick, startTimer, stopTimer, updateTimer } = useTimer()

const setUserToRoadPartIsLoading = ref(false)
const scanStaggingAreaIsLoading = ref(false)
const pickingBagIsLoading = ref(false)

const currentPair = ref(null)

const sidePanelRef = ref(null)

const STORAGE_KEY = 'currentBagPickedId'

const currentBagPickedId = ref(null)

const confirmFinishPickDialogRef = ref(null);

onMounted(() => {
  const raw = localStorage.getItem(STORAGE_KEY)
  currentBagPickedId.value = raw ? JSON.parse(raw) : null
})

const globalLoading = computed(() => {
  return scanStaggingAreaIsLoading.value
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

const bagsNoPicked = computed(() =>
  currentRoadPart.value?.bags.filter(b => b.picked !== true) ?? []
)

const currentBag = computed(() =>
  currentRoadPart.value?.bags.find(b => b.picked !== true) ?? null
)

const currentRoadPartTitle = computed(() =>
  `${currentRoadPart.value.road} #${currentRoadPart.value.number}`
)

const nbOfBags = computed(() =>
  currentRoadPart.value ? currentRoadPart.value?.bags?.length : '0'
)

const roadPartNotice = computed(() => {
  if (allBagsPicked.value) {
    return `Go to stage in the area STG-${currentRoadPart.value.stagging.name}`
  }
  return !currentRoadPart.value.cart
    ? `Take a cart in the stagging area STG-${currentRoadPart.value.stagging.name}`
    : 'Pick the next bag'
})

const roadPartStats = computed(() => {
  return [
    { 'title': 'Number of bags', 'number': `${nbOfBags.value - bagsNoPicked.value.length}`, 'number_2': `/${nbOfBags.value}` },
    { 'title': 'Time to picking', 'number': `${timeToPick.value}` },
  ]
})

const allBagsPicked = computed(() => {
  return currentRoadPart?.value.bags.every(bag => bag.picked === true)
})

const setCurrentPair = (pair) => {
  currentPair.value = pair
  sidePanelRef.value?.toggleSidePanel()
}

const scanStaggingArea = (stagging) => {
  currentRoadPart.value.cart ? staggingCart(stagging) : setCartToRoadPart(stagging);
}

const resetCurrentBagPickedId = () => {
  currentBagPickedId.value = null
}

const scanBag = (bagId) => {
  if (!bagId) {
    return
  }

  if (currentBag.value?.id !== bagId) {
    resetCurrentBagPickedId()
    setTimeout(() => {
      notifier('error', 'Error picking', `Wrong bag`)
    }, 500);
  } else if (currentBagPickedId.value) {
    resetCurrentBagPickedId()
    setTimeout(() => {
      notifier('error', 'Error picking', `Scan your bag`)
    }, 500);
  } else {
    currentBagPickedId.value = bagId
  }
}

async function setUserToRoadPart() {

  setUserToRoadPartIsLoading.value = true;

  const { data, error } = await usePostFetch(`/setRoadToUser`)

  if (error.value) {
    setTimeout(() => {
      notifier('error', 'RoadPart', ` ${error.value}`)
    }, 1000);
    setTimeout(() => {
      setUserToRoadPartIsLoading.value = false;
    }, 1500);
  }

  if (data.value) {
    currentRoadPart.value = data.value

    setTimeout(() => {
      notifier('success', 'RoadPart', `The road part  ${currentRoadPart.value.name} is ready to pick`)
    }, 1000);
    setTimeout(() => {
      setUserToRoadPartIsLoading.value = false;
    }, 1500);
  }
}

async function setCartToRoadPart(stagging) {

  scanStaggingAreaIsLoading.value = true

  if (!currentRoadPart.value) {
    scanStaggingAreaIsLoading.value = false;
    return
  }

  const { data, error } = await usePostFetch(`/setCartToRoadPart/${currentRoadPart.value.id}`, { staggingId: stagging?.id ?? null })

  if (error.value) {
    setTimeout(() => {
      notifier('error', 'Wrong cart', `${error.value}`)
    }, 1000);
    setTimeout(() => {
      scanStaggingAreaIsLoading.value = false;
      return
    }, 1500);
  }

  if (data.value) {
    currentRoadPart.value = data.value
    setTimeout(() => {
      notifier('success', 'Cart', `The cart is set !`)
    }, 1000);
    setTimeout(() => {
      scanStaggingAreaIsLoading.value = false;
      // Proposer de prendre une route
    }, 1500);
  }

}

async function staggingCart(stagging) {

  scanStaggingAreaIsLoading.value = true

  if (!currentRoadPart.value) {
    scanStaggingAreaIsLoading.value = false;
    return
  }

  const { data, error } = await usePostFetch(`/staggingCart/${currentRoadPart.value.id}`, { staggingId: stagging?.id ?? null })

  if (error.value) {
    setTimeout(() => {
      notifier('error', 'Error stagging', `${error.value}`)
    }, 1000);
    setTimeout(() => {
      scanStaggingAreaIsLoading.value = false;
      return
    }, 1500);
  }

  if (data.value) {
    currentRoadPart.value = data.value.roadPart
    setTimeout(() => {
      notifier('success', 'Cart', `The road ${currentRoadPartTitle.value} is stagged`)
    }, 1000);
    setTimeout(() => {
      // Question to finish picking
      scanStaggingAreaIsLoading.value = false;
      stopTimer();
    }, 1500);
  }

}

async function pickingBag(cart) {

  if (!currentBagPickedId.value) {
    return
  }

  pickingBagIsLoading.value = true;

  const currentBagPickedName = currentBag.value.name;

  const { data, error } = await usePostFetch(`/pickingBag/${cart}`, { bagId: currentBagPickedId.value ?? null })

  if (error.value) {
    setTimeout(() => {
      notifier('error', 'Error picking', `${error.value}`)
    }, 1000);
    setTimeout(() => {
      pickingBagIsLoading.value = false;
      currentBagPickedId.value = null;
      return
    }, 1500);
  }

  if (data.value) {
    currentRoadPart.value = data.value
    setTimeout(() => {
      notifier('success', 'Bag', `The bag ${currentBagPickedName} is picked`)
    }, 500);
    setTimeout(() => {
      pickingBagIsLoading.value = false;
      sidePanelRef.value?.toggleSidePanel();
    }, 600);
  }
}

provide('picking', { currentPair, scanStaggingArea, currentRoadPart, allBagsPicked, globalLoading })

const handleToggle = () => {

  if (!sidePanelRef.value?.isOpen) {
    resetCurrentBagPickedId();
  }
}

watchEffect(handleToggle)

watch(
  currentBagPickedId,
  (val) => {
    if (val === null) {
      localStorage.removeItem(STORAGE_KEY)
    } else {
      localStorage.setItem(STORAGE_KEY, JSON.stringify(val))
    }
  },
  { deep: true }
)

watch(
  currentRoadPart,
  (val) => {
    console.log('currentRoadPart', val)
    if (val?.pickingStartedAt?.date) {
      startTimer(val.pickingStartedAt.date)
    }
    if (val?.pickingDurationSeconds !== null) {
      durationSeconds.value = val?.pickingDurationSeconds
    }
  },
  { immediate: true, deep: true }
)

</script>
