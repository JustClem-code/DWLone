<template>
  <div
    class="w-full min-h-30 p-4 relative flex bg-white/40 dark:bg-gray-800/50 border shadow-xs dark:shadow-none border-gray-300 dark:border-gray-700/90 rounded-lg"
    :class="[
      modelValue === option.value
        ? 'outline-2 -outline-offset-2 outline-blue-500'
        : '',
      option.disabled
        ? 'cursor-not-allowed pointer-events-none opacity-50'
        : 'cursor-pointer',
    ]" :tabindex="option.disabled ? -1 : tabindex" role="radio" :aria-checked="modelValue === option.value"
    :aria-disabled="option.disabled" @click="selectOption" @keydown="handleKeydown">
    
    <input :id="radioId" ref="radioEl" type="radio" :name="groupName" :value="option.value"
      :checked="modelValue === option.value" :disabled="option.disabled" tabindex="-1"
      class="pointer-events-none absolute top-6 right-6 size-1 appearance-none" />

    <div class="flex-1 pl-8" :aria-label="option.value" :aria-description="option.notice">
      <span class="block text-sm font-medium">
        {{ option.value }}
      </span>

      <span class="block text-sm mt-1 text-gray-500 dark:text-gray-400">
        {{ option.notice }}
      </span>

      <span class="block text-sm mt-4">
        {{ option.number }}
      </span>
    </div>

    <CheckCircleFillIcon v-if="modelValue === option.value" size="size-5" color="text-blue-500"
      class="z-10 absolute top-4 right-4" />

    <CheckCircleFillIcon v-else-if="!option.disabled" size="size-5" color="text-red-500"
      class="z-10 absolute top-4 right-4" />
  </div>
</template>

<script setup>
import { computed, ref } from 'vue';
import CheckCircleFillIcon from '../Icons/CheckCircleFillIcon.vue';

const props = defineProps({
  option: {
    type: Object,
    required: true,
  },

  modelValue: {
    type: String,
    default: null,
  },

  groupName: {
    type: String,
    default: 'automaticOptions',
  },

  tabindex: {
    type: Number,
    default: -1,
  },
});

const emit = defineEmits([
  'update:modelValue',
  'radio-keydown',
]);

const radioEl = ref(null);

const radioId = computed(() => `radio-${props.option.value}`);

function selectOption() {
  if (props.option.disabled) return;

  emit('update:modelValue', props.option.value);
}

function handleKeydown(event) {
  emit('radio-keydown', event);
}

defineExpose({
  radioEl,
});
</script>