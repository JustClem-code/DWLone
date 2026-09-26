<template>
    <form @submit.prevent="emit('submitForm')" class="flex flex-col gap-2 mb-8 z-10" role="radiogroup"
        aria-label="Automatic options">

        <RadioCard v-for="option in options" :key="option.value" :option="option" :model-value="modelValue"
            @update:model-value="selectOption" :ref="el => setRadioCardRef(el, option.value)"
            :tabindex="getTabIndex(option)" @radio-keydown="onRadioKeydown" />

        <BaseButton type="submit" class="mt-4" title="Automatic program" styleColor="primary" :isDisabled="!modelValue"
            :isLoading="isLoading" />
    </form>
</template>

<script setup>
import { ref, computed, watch, nextTick } from 'vue';

import BaseButton from './Buttons/BaseButton.vue';
import RadioCard from './Radios/RadioCard.vue';

const emit = defineEmits(['submitForm', 'update:modelValue']);

const props = defineProps({
    options: {
        type: Array,
        required: true,
    },
    modelValue: {
        type: String,
        default: null,
    },
    sidePanelRef: {
        type: Object,
        required: false,
    },
    isLoading: {    
        type: Boolean,
        default: false,
    },
})

const radioCardRefs = ref({});

const focusedOption = ref(null);

const firstEnabledOption = computed(() =>
    props.options.find(option => !option.disabled)
);

const enabledOptions = computed(() =>
    props.options.filter(option => !option.disabled)
);

const setRadioCardRef = (component, value) => {
    if (component) {
        radioCardRefs.value[value] = component;
    } else {
        delete radioCardRefs.value[value];
    }
}

const getTabIndex = (option) => {
    if (option.disabled) return -1;

    if (focusedOption.value === option.value) return 0;

    if (!focusedOption.value && props.modelValue === option.value) return 0;

    if (
        !focusedOption.value &&
        !props.modelValue &&
        firstEnabledOption.value?.value === option.value
    ) {
        return 0;
    }

    return -1;
}

const focusRadioCard = (value) => {
    const radioCard = radioCardRefs.value[value];

    if (!radioCard) return;

    radioCard.$el?.focus();
}


const focusOption = (value) => {
    const option = props.options.find(
        option => option.value === value
    );

    if (!option || option.disabled) return;

    focusedOption.value = option.value;

    nextTick(() => {
        focusRadioCard(option.value);
    });
}

const selectOption = (value) => {
    const option = props.options.find(
        option => option.value === value
    );

    if (!option || option.disabled) return;

    emit('update:modelValue', option.value);
}

const onRadioKeydown = (event) => {
    const focusedValue = focusedOption.value;

    if (event.key === 'Enter') {
        event.preventDefault();

        if (props.modelValue && !props.isLoading) {
            emit('submitForm');
        }

        return;
    }

    if (event.key === ' ' || event.key === 'Spacebar') {
        event.preventDefault();

        if (focusedValue) {
            selectOption(focusedValue);
        }

        return;
    }

    if (!['ArrowDown', 'ArrowUp', 'ArrowLeft', 'ArrowRight'].includes(event.key)) {
        return;
    }

    event.preventDefault();

    if (!enabledOptions.value.length) return;

    const currentIndex = enabledOptions.value.findIndex(
        option => option.value === focusedValue
    );

    const safeCurrentIndex = currentIndex === -1 ? 0 : currentIndex;

    const isNext = ['ArrowDown', 'ArrowRight'].includes(event.key);
    const offset = isNext ? 1 : -1;

    const nextIndex =
        (safeCurrentIndex + offset + enabledOptions.value.length) %
        enabledOptions.value.length;

    focusOption(enabledOptions.value[nextIndex].value);
}

watch(
    () => props.options,
    options => {
        const firstEnabled = options.find(option => !option.disabled);

        if (!firstEnabled) {
            emit('update:modelValue', null);
            focusedOption.value = null;
            return;
        }

        const selectedOption = options.find(
            option => option.value === props.modelValue
        );

        if (!selectedOption || selectedOption.disabled) {
            emit('update:modelValue', null);
        }

        const focusedCardOption = options.find(
            option => option.value === focusedOption.value
        );

        if (!focusedCardOption || focusedCardOption.disabled) {
            focusedOption.value = firstEnabled.value;
        }
    },
    {
        immediate: true,
        deep: true,
    }
);

watch(
    () => props.sidePanelRef?.isOpen,
    async isOpen => {
        if (!isOpen) {
            emit('update:modelValue', null);
            focusedOption.value = null;
            return;
        }

        await nextTick();

        const firstEnabled = firstEnabledOption.value;

        if (firstEnabled) {
            focusOption(firstEnabled.value);
        }
    }
);

</script>