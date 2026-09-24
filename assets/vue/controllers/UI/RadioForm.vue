<template>
    <form @submit.prevent="('submitForm')" class="flex flex-col gap-2 mb-8 z-10" role="radiogroup"
        aria-label="Automatic options">

        <RadioCard v-for="option in automaticOptions" :key="option.value" :option="option" v-model="selected"
            group-name="automaticOptions" :ref="el => setRadioCardRef(el, option.value)" :tabindex="getTabIndex(option)"
            @radio-keydown="onRadioKeydown" />

        <BaseButton type="submit" class="mt-4" title="Automatic program" styleColor="primary" :isDisabled="!selected"
            :isLoading="isLoading" />
    </form>
</template>

<script setup>
import BaseButton from './Buttons/BaseButton.vue';
import RadioCard from './Radios/RadioCard.vue';

const emit = defineEmits(['submitForm']);

const props = defineProps({
  isLoading: Boolean
})


</script>