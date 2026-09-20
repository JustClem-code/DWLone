<template>
  <div
    class="w-full min-h-30 p-4 relative flex bg-white/40 dark:bg-gray-800/50 border shadow-xs dark:shadow-none border-gray-300 dark:border-gray-700/90 rounded-lg"
    :class="[
      modelValue === option.value ? 'outline-2 -outline-offset-2 outline-blue-500' : '',
      option.disabled ? 'cursor-not-allowed pointer-events-none opacity-50' : ''
    ]" tabindex="0">
    <!-- Vrai input radio -->
    <input :id="radioId" ref="radioEl" type="radio" :name="groupName" :value="option.value"
      :checked="modelValue === option.value" :disabled="option.disabled"
      @change="$emit('update:modelValue', option.value)"
      class="z-10 absolute top-6 right-6 appearance-none size-1 cursor-pointer disabled:cursor-not-allowed" />

    <!-- Contenu de la carte, cliquable via label -->
    <label :for="radioId" class="flex-1 pl-8 cursor-pointer" aria-label="Option card" :aria-description="option.notice">
      <span class="block text-sm font-medium">{{ option.value }}</span>
      <span class="block text-sm mt-1 text-gray-500 dark:text-gray-400">{{ option.notice }}</span>
      <span class="block text-sm mt-4">{{ option.number }}</span>
    </label>

    <CheckCircleFillIcon size="size-5" color="text-blue-500" v-show="modelValue === option.value"
      class="z-100 absolute top-4 right-4" />

    <CheckCircleFillIcon size="size-5" color="text-red-500" v-show="modelValue !== option.value && !option.disabled"
      class="z-100 absolute top-4 right-4" />
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import CheckCircleFillIcon from '../Icons/CheckCircleFillIcon.vue';

const props = defineProps({
  option: Object,
  modelValue: String,
  groupName: { type: String, default: 'automaticOptions' },
});

const emit = defineEmits(['update:modelValue']);

const radioEl = ref(null);
defineExpose({ radioEl });

const radioId = computed(() => `radio-${props.option.value}`);
</script>