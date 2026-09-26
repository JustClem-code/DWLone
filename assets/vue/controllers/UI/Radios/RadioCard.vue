<template>
  <div :id="radioId" role="radio" :tabindex="option.disabled ? -1 : tabindex" :aria-checked="isSelected"
    :aria-disabled="option.disabled ? 'true' : 'false'" :aria-labelledby="labelId" :aria-describedby="noticeId"
    class="relative flex w-full min-h-30 rounded-lg border bg-white/40 p-4 shadow-xs transition dark:border-gray-700/90 dark:bg-gray-800/50 dark:shadow-none focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-500"
    :class="[
      isSelected
        ? 'border-blue-500 inset-ring-2 -inset-ring-offset-2 inset-ring-blue-500'
        : 'border-gray-300',
      option.disabled
        ? 'cursor-not-allowed opacity-50'
        : 'cursor-pointer',
    ]" @click="selectOption" @keydown="handleKeydown">
    <div class="flex-1 pr-8">
      <span :id="labelId" class="block text-sm font-medium">
        {{ option.value }}
      </span>

      <span v-if="option.notice" :id="noticeId" class="mt-1 block text-sm text-gray-500 dark:text-gray-400">
        {{ option.notice }}
      </span>

      <span v-if="option.number" class="mt-4 block text-sm">
        {{ option.number }}
      </span>
    </div>

    <CheckCircleFillIcon v-if="isSelected" aria-hidden="true" size="size-5" color="text-blue-500"
      class="absolute top-4 right-4" />

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

const normalizedValue = computed(() =>
  String(props.option.value)
    .trim()
    .toLowerCase()
    .replace(/\s+/g, '-'),
);

const radioId = computed(() => `radio-${normalizedValue.value}`);
const labelId = computed(() => `${radioId.value}-label`);
const noticeId = computed(() => `${radioId.value}-notice`);

const isSelected = computed(() =>
  props.modelValue === props.option.value,
);

const selectOption = () => {
  if (props.option.disabled) return;

  emit('update:modelValue', props.option.value);
};

const handleKeydown = (event) => {
  emit('radio-keydown', event);
};

defineExpose({
  radioEl,
});
</script>