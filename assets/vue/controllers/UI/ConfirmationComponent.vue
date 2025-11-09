<template>
  <div v-show="question" class="mb-4">
    <p>{{ question }}</p>
  </div>
  <div class="flex justify-end gap-2" @keydown.enter.prevent="confirm" tabindex="0">
    <BaseButton title="Cancel" styleColor="empty" @click="cancel" />
    <BaseButton title="Confirm" styleColor="danger" @click="confirm" />
  </div>
</template>

<script setup>
import BaseButton from './Buttons/BaseButton.vue';

const props = defineProps({
  question: String
})

const emit = defineEmits(['confirm', 'cancel']);

const confirm = () => {
  emit('confirm');
};

const cancel = () => {
  emit('cancel');
};
</script>
