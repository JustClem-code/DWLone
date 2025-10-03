<template>
  <button @click="openDialog" class="w-full p-2">{{ title ?? "Option" }}</button>
  <dialog ref="myDialog">
    <div class="fixed h-full w-full" @click="closeDialog"></div>
    <div
      class="bg-white p-8 rounded-2xl shadow-2xl max-w-[90vw] max-h-[90vh] fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-10">
      <div class="flex justify-center items-center w-50">
        <form method="dialog">
          <div v-for="option in options" :key="option.id">
            <button @click="selectOption(option)">{{ option.name ?? (option.wrid ?? 'option') }}</button>
          </div>
          <button @click="closeDialog">Fermer</button>
        </form>
      </div>
    </div>
  </dialog>
</template>

<script setup>
import { ref } from 'vue'

const props = defineProps({
  title: String,
  options: Array
})

const emit = defineEmits(['submitOption'])

function selectOption(selected) {
  emit('submitOption', { selected })
}

const myDialog = ref(null);

const openDialog = () => {
  myDialog.value?.showModal();
};
const closeDialog = () => {
  myDialog.value?.close();
};

</script>
