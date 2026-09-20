<template>
  <div class="flex flex-col gap-2">
    <StatsHeader
      title="Packages statistics"
      notice="You can automate the steps"
      actionTitle="Automating steps"
      @actionClick="sidePanelRef?.toggleSidePanel()"
      :statistics="packagesStats"
    />

    <SidePanel ref="sidePanelRef" title="Automating steps">
      <form
        @submit.prevent="submitAutomaticForm"
        class="flex flex-col gap-2 mb-8 z-10"
        role="radiogroup"
        aria-label="Automatic options"
      >
        <RadioCard
          v-for="(option, index) in automaticOptions"
          :key="option.value"
          :option="option"
          v-model="selected"
          :group-name="'automaticOptions'"
          :ref="el => { if (index === 0) firstRadioCardRef = el }"
          @keydown="onRadioKeydown"
          tabindex="0"
        />

        <BaseButton
          type="submit"
          class="mt-4"
          title="Automatic program"
          styleColor="primary"
          :isDisabled="!selected"
          :isLoading="globalLoading"
        />
      </form>
    </SidePanel>
  </div>
</template>

<script setup>
import { ref, computed, watch, watchEffect, nextTick } from 'vue';
import { usePostFetch } from '../../composables/fetch.js';
import { useNotification } from '../../composables/eventBus.js';
import { dashboardStore } from '../../composables/dashboardStore.js';

import BaseButton from '../UI/Buttons/BaseButton.vue';
import RadioCard from '../UI/Radios/RadioCard.vue';
import SidePanel from '../UI/SidePanel.vue';
import StatsHeader from './StatsHeader.vue';

const { locations, allPackagesStats, updateDashboardData } = dashboardStore();
const { notifier } = useNotification();

const STORAGE_KEY_PALLET = 'currentPallet';

const sidePanelRef = ref(null);
const firstRadioCardRef = ref(null);

const selected = ref(null);
const globalLoading = ref(null);

/* ------------------ Stats & options ------------------ */

const allPackagesNumber = computed(() =>
  allPackagesStats.value ? allPackagesStats.value.allPackagesNumber : 0
);

const packagesWithoutLocationNumber = computed(() =>
  allPackagesStats.value ? allPackagesStats.value.packagesWithoutLocationNumber : 0
);

const packagesWithLocationNotStowedNumber = computed(() =>
  allPackagesStats.value ? allPackagesStats.value.packagesWithLocationNotStowedNumber : 0
);

const packagesWithLocationNumber = computed(() =>
  allPackagesStats.value ? allPackagesStats.value.packagesWithLocationNumber : 0
);

const packagesWithLocationAndStowedNumber = computed(() =>
  allPackagesStats.value ? allPackagesStats.value.packagesWithLocationAndStowedNumber : 0
);

const packagesToResetNumber = computed(() =>
  allPackagesStats.value ? packagesWithLocationNumber.value : 0
);

const packagesFullAutomatingNumber = computed(() =>
  allPackagesStats.value
    ? packagesWithLocationNotStowedNumber.value >= packagesWithoutLocationNumber.value
      ? packagesWithLocationNotStowedNumber.value
      : packagesWithoutLocationNumber.value
    : 0
);

const inductPercentage = computed(() => {
  if (allPackagesNumber.value === 0) return 0;
  return Math.round((packagesWithLocationNumber.value / allPackagesNumber.value) * 100);
});

const stowPercentage = computed(() =>
  !allPackagesNumber.value || !inductPercentage.value
    ? 0
    : Math.round(
        (packagesWithLocationAndStowedNumber.value / packagesWithLocationNumber.value) * 100
      )
);

const packagesStats = computed(() => [
  { title: 'Number of packages', number: `${allPackagesNumber.value}` },
  { title: 'Induct progress', number: `${inductPercentage.value}%` },
  { title: 'Stow progress', number: `${stowPercentage.value}%` },
]);

const automaticOptions = computed(() => [
  {
    value: 'Induct',
    notice: 'Automating of pallet induct on floor',
    number: `${packagesWithoutLocationNumber.value}`,
    disabled: packagesWithoutLocationNumber.value === 0,
  },
  {
    value: 'Stow',
    notice: 'Automating of packages stow',
    number: `${packagesWithLocationNotStowedNumber.value}`,
    disabled: packagesWithLocationNotStowedNumber.value === 0,
  },
  {
    value: 'Full',
    notice: 'Automating every step',
    number: `${packagesFullAutomatingNumber.value}`,
    disabled: packagesFullAutomatingNumber.value === 0,
  },
  {
    value: 'Hard reset',
    notice: 'Reset all steps',
    number: `${packagesToResetNumber.value}`,
    disabled: packagesToResetNumber.value === 0,
  },
]);

/* ------------------ Actions ------------------ */

function submitAutomaticForm() {
  const actions = {
    Induct: () => automaticInduction(),
    Stow: () => automaticStow(),
    Full: () => autoInductionAndStow(),
    'Hard reset': () => resetLocationsBagsPackages(),
  };

  const run = actions[selected.value];

  if (!run) {
    console.log('error');
    return;
  }

  run();
  selected.value = null;
}

const resetLocalStorage = () => {
  localStorage.removeItem(STORAGE_KEY_PALLET);
};

async function automaticInduction() {
  globalLoading.value = true;

  const { data, error } = await usePostFetch('/automaticinduction');

  if (error.value) {
    notifier('error', 'Automatic induction', 'An error occurred');
    globalLoading.value = false;
    return;
  }

  if (data.value) {
    updateDashboardData(data);
    notifier('success', 'Automatic induction', 'Induction is finished!!!');
    globalLoading.value = false;
    sidePanelRef.value?.toggleSidePanel();
  }
}

async function automaticStow() {
  globalLoading.value = true;

  const { data, error } = await usePostFetch('/automaticstow');

  if (error.value) {
    notifier('error', 'Automatic stow', 'An error occurred');
    globalLoading.value = false;
    return;
  }

  if (data.value) {
    updateDashboardData(data);
    notifier('success', 'Automatic stow', 'Stow is finished!!!');
    globalLoading.value = false;
    sidePanelRef.value?.toggleSidePanel();
  }
}

async function autoInductionAndStow() {
  globalLoading.value = true;

  const { data, error } = await usePostFetch('/autoinductionandstow');

  if (error.value) {
    notifier('error', 'Automatic induct and stow', 'An error occurred');
    globalLoading.value = false;
    return;
  }

  if (data.value) {
    updateDashboardData(data);
    notifier('success', 'Automatic induct and stow', 'Induct and stow are finished!!!');
    globalLoading.value = false;
    sidePanelRef.value?.toggleSidePanel();
  }
}

async function resetLocationsBagsPackages() {
  globalLoading.value = true;

  const { data, error } = await usePostFetch('/hardResetLocationsBagsPackages');

  if (data.value) {
    resetLocalStorage();
    globalLoading.value = false;
    notifier('success', 'Hard reset', 'The reset is finished');
    updateDashboardData(data);
    sidePanelRef.value?.toggleSidePanel();
  }
}

/* ------------------ Navigation clavier ------------------ */

const firstEnabledOption = computed(() =>
  automaticOptions.value.find(o => !o.disabled)
);

// Options activables (valeurs)
const enabledOptions = computed(() =>
  automaticOptions.value.filter(o => !o.disabled).map(o => o.value)
);

// Index de l'option sélectionnée dans enabledOptions
const currentEnabledIndex = computed(() =>
  selected.value ? enabledOptions.value.indexOf(selected.value) : -1
);

function onRadioKeydown(e) {
  if (!['ArrowDown', 'ArrowUp'].includes(e.key)) return;

  e.preventDefault();

  if (!enabledOptions.value.length) return;

  let nextIndex = currentEnabledIndex.value;

  // Si rien n'est sélectionné, on part du premier
  if (nextIndex === -1) nextIndex = 0;

  if (e.key === 'ArrowDown') {
    nextIndex = (nextIndex + 1) % enabledOptions.value.length;
  } else if (e.key === 'ArrowUp') {
    nextIndex = (nextIndex - 1 + enabledOptions.value.length) % enabledOptions.value.length;
  }

  const nextValue = enabledOptions.value[nextIndex];
  if (nextValue) {
    selected.value = nextValue;
  }
}

/* ------------------ Watchers ------------------ */

const handleToggle = () => {
  if (!sidePanelRef.value?.isOpen) {
    selected.value = null;
  }
};

watchEffect(handleToggle);

// Sélectionner la première option disponible au chargement
watchEffect(() => {
  if (!selected.value && firstEnabledOption.value) {
    selected.value = firstEnabledOption.value.value;
  }
});

// Focus sur le premier radio à l'ouverture du panel
watch(
  () => sidePanelRef.value?.isOpen,
  async (isOpen) => {
    if (!isOpen) return;
    await nextTick();

    if (!firstEnabledOption.value) return;
    if (!firstRadioCardRef.value) return;

    // RadioCard expose radioEl (ref sur l'input)
    const input = firstRadioCardRef.value.radioEl;
    input?.focus();
  },
  { immediate: false }
);
</script>