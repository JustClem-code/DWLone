<template>
  <ul role="list" class="divide-y">
    <li v-for="truck in trucks" :key=truck.id
      class="flex items-center justify-between py-4 border-gray-200 dark:border-gray-700/90">
      <div class="">
        <div class="flex items-center gap-4 text-base font-semibold">
          <p>{{ truck.wrid }}</p>
          <BadgeComponent :type="badgeType(truck)" :title="truck.dock ?? 'Waiting dock'" size="sm" />
        </div>
        <p class="text-sm font-light text-gray-800 dark:text-gray-400">date</p>
      </div>
      <div class="flex items-center gap-2">
        <SelectDialogComponent title="Docks" :options="docks" :isNotDocked="!truck.dock" :isLoading="dockingIsLoading" :disabled="true"
          styleColorButton="empty" sizeButton="sm" @submitOption="val => dockingTruck(truck, val.selected)" />
        <MinimalToggleMenu :items="menuItems" @click="defineCurrentTruck(truck)" @select="handleMenuAction" />
      </div>
    </li>
  </ul>
</template>

<script setup>
import { inject, ref, toRef, computed } from 'vue'
import BadgeComponent from './BadgeComponent.vue';
import SelectDialogComponent from './SelectDialogComponent.vue';
import MinimalToggleMenu from './MinimalToggleMenu.vue';

const { trucks, dockingTruck } = inject('yardTruck')

const yardTruck = inject('yardTruck')
const dockingIsLoading = toRef(yardTruck, 'dockingIsLoading')

const props = defineProps({
  trucks: Array,
  docks: Array,
})

const currentTruck = ref(null)

function badgeType(truck) {
  return truck.dock ? 'warning' : 'valid';
}

function defineCurrentTruck(truck) { currentTruck.value = truck }

const menuItems = [
  { label: 'Undocking', action: 'unDocking' },
  /* { label: 'Editer', action: 'editItem' },
  { label: 'Partager', action: 'shareItem' } */
]


const unDocking = () => dockingTruck(currentTruck.value, null)
const editItem = () => console.log('Édition')
const shareItem = () => console.log('Partage')


const handleMenuAction = (action) => {
  const actions = { unDocking, editItem, shareItem }
  if (actions[action]) actions[action]() // Appelle dynamiquement la méthode correspondante
}


/*
const badgeType = computed(() => {
  if (!props.dock) return null;
  return !props.dock.truckWrid ? 'valid' : 'warning';
})

const badgeTitle = computed(() => {
  if (!props.dock) return null;
  return !props.dock.truckWrid ? 'Free' : 'Used';
}) */

</script>
