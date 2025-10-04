<template>
  <select v-model="selectedObjet" @change="onChange" class="form-select block w-full mt-1 rounded border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
    <option disabled value="">Choose</option>
    <option v-for="option in options" :key="option.id" :value="option">{{ option.name ?? 'optioo' }}</option>
  </select>
</template>

<script setup>
import { ref } from 'vue'

const props = defineProps({
  options: Array
})

const emit = defineEmits(['submitOption'])

const selectedObjet = ref(null)

function onChange() {
  const selected = selectedObjet.value
  emit('submitOption', { selected })
}
</script>
