<template>
  <label aria-label="Newsletter" aria-description="Last message sent an hour ago to 621 users"
    class="w-full p-4 relative flex bg-white/40 dark:bg-gray-800/50 border shadow-xs dark:shadow-none border-gray-300 dark:border-gray-700/90 rounded-lg"
    :class="modelValue === option.value ? 'outline-2 -outline-offset-2 outline-blue-500' : '',
      option.disabled ? 'cursor-not-allowed pointer-events-none opacity-50' : ''">
    <input type="radio" :name="option.value" :value="option.value" :checked="modelValue === option.value"
      @change="$emit('update:modelValue', option.value)" class="absolute inset-0 appearance-none"
      :class="option.disabled ? 'cursor-not-allowed' : 'cursor-pointer'">
    <div class="flex-1">
      <span class="block text-sm font-medium">{{ option.value }}</span>
      <span class="block text-sm mt-1 text-gray-500 dark:text-gray-400">{{ option.notice }}</span>
      <span class="block text-sm mt-4">{{ option.number }}</span>
    </div>
    <CheckCircleFillIcon size="size-5" color="text-blue-500" v-show="modelValue === option.value" />
  </label>
</template>

<script setup>
import { onMounted } from 'vue';
import CheckCircleFillIcon from '../Icons/CheckCircleFillIcon.vue';

const props = defineProps({
  option: Object,
  modelValue: String,
})

onMounted(() => {
  console.log('option', props.option);

})

const emit = defineEmits(['update:modelValue'])

</script>
