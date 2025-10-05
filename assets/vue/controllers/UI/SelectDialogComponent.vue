<template>
  <button @click="openDialog" class="w-full p-2 disabled:opacity-25" :disabled="!disabled">{{ title ?? "Option" }}</button>
  <dialog ref="myDialog">
    <div class="fixed h-full w-full" @click="closeDialog"></div>
    <div
      class="bg-white max-w-[90vw] max-h-[90vh] fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-10 p-8 rounded-2xl shadow-2xl">
      <div class="flex w-full">
        <form @submit.prevent="submitOp" class="flex flex-col gap-2">
          <button v-for="option in options" :key="option.id" type="button" @click="selectOption(option)"
            class="w-full text-base border border-solid border-gray-100 hover:border-gray-300 hover:text-blue-500 p-2 rounded-md"
            :class="{ 'border-2 border-slate-500 text-blue-700 hover:border-slate-500 hover:text-blue-700': selected?.id === option.id }">
            {{ option.name ?? (option.wrid ?? 'option') }}
          </button>
          <div class="flex gap-2">
            <button type="button"
              class="bg-white hover:bg-gray-50 border border-solid border-gray-300 text-sm font-semibold py-2 px-3 rounded-md"
              @click="closeDialog">Cancel</button>
            <button type="submit" :disabled="!selected"
              class="text-white bg-blue-500 hover:bg-blue-700 disabled:bg-blue-100 text-sm font-semibold py-2 px-3 rounded-md">Submit</button>
          </div>
        </form>
      </div>
    </div>
  </dialog>
</template>

<script setup>
import { ref } from 'vue'

const props = defineProps({
  title: String,
  options: Array,
  disabled: Boolean
})

const emit = defineEmits(['submitOption'])

const myDialog = ref(null);

const openDialog = () => {
  myDialog.value?.showModal();
};
const closeDialog = () => {
  myDialog.value?.close();
  selected.value = null
};

const selected = ref(null)

function selectOption(option) {
  selected.value = option
}

function submitOp() {
  emit('submitOption', { selected: selected.value })
  closeDialog()
}
</script>
