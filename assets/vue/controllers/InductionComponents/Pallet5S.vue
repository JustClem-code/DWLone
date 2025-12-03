<template>
  <BorderedContent v-if="currentPallet" title="Pallet 5S" minH="min-h-60">

    <div draggable="true" @dragstart="(e) => onDragStart(e, currentPallet.packages[0])">
      <p>{{ currentPallet.packages[0] }}</p>
    </div>

  </BorderedContent>

  <button v-else type="button" @click="SelectOptionRef?.openDialog()"
    class="relative flex flex-col justify-center items-center w-full min-h-60 border-2 border-dashed border-gray-200 dark:border-gray-700/90 rounded-xl p-4 sm:p-8">
    <AddDatabaseIcon size="size-16" color="text-gray-200 dark:text-gray-700/90" />
    <span class="">Add a pallet</span>
  </button>

  <DialogComponentSlot ref="SelectOptionRef">
    <SelectOptionComponent :options="palletsOnFloor" :isLoading="addPalletLoading"
      @submitOption="val => addPallet(val.selected)" @closeDialog="SelectOptionRef?.closeDialog()" />
  </DialogComponentSlot>

</template>

<script setup>
import { inject, ref, computed } from 'vue'
import { useDragStore } from '../../composables/useDragStore.js';
import AddDatabaseIcon from '../UI/Icons/AddDatabaseIcon.vue';
import DialogComponentSlot from '../UI/Modals/DialogComponentSlot.vue';
import SelectOptionComponent from '../UI/Modals/SelectOptionComponent.vue';
import BorderedContent from '../UI/BorderedContent.vue';

const props = defineProps({
  currentPallet: Object
});

const { palletsOnFloor, addPalletLoading, addPallet } = inject('induction')

const SelectOptionRef = ref(null)

const { setDraggedItem } = useDragStore();

function onDragStart(event, item) {
  setDraggedItem(item);
  event.dataTransfer.effectAllowed = 'move';
}
</script>
