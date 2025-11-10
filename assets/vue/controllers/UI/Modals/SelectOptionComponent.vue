<template>
    <div class="flex">
      <form @submit.prevent="submitOp" class="flex flex-col gap-2 min-w-60">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-2">
          <button v-for="option in options" :key="option.id" type="button" @click="selectOption(option)"
            class="w-full text-base inset-ring inset-shadow-sm transition duration-150 ease-in-out inset-ring-gray-100 hover:inset-ring-gray-300 text-gray-900 dark:text-gray-100 hover:text-blue-500 p-2 rounded-md"
            :class="{ 'inset-ring-2 inset-ring-slate-500 text-blue-700 hover:inset-ring-slate-500 hover:text-blue-700': selected?.id === option.id }">
            {{ option.name ?? 'option' }}
          </button>
        </div>
        <div class="flex gap-2">
          <BaseButton title="Cancel" styleColor="empty" @click="emit('closeDialog')" />
          <BaseButton buttonType="submit" title="Submit" styleColor="primary" :isDisabled="!selected || isLoading"
            :isLoading="isLoading" />
        </div>
      </form>
    </div>
</template>

<script setup>
import { ref, watch } from 'vue';
import BaseButton from './../Buttons/BaseButton.vue';

const props = defineProps({
  options: Array,
  isLoading: Boolean
});

const emit = defineEmits(['submitOption', 'closeDialog']);

const shouldCloseOnLoad = ref(false);

const selected = ref(null);

function selectOption(option) {
  selected.value = option;
}

function submitOp() {
  emit('submitOption', { selected: selected.value });
  shouldCloseOnLoad.value = true;
}

watch(() => props.isLoading, (val) => {
  if (val === false && shouldCloseOnLoad.value) {
    emit('closeDialog');
    shouldCloseOnLoad.value = false;
  }
});
</script>
