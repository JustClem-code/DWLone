<template>
  <div class="flex flex-col gap-2">
    <StatsHeader title="Packages statistics" notice="You can automate the steps" actionTitle="Automating steps"
      @actionClick="sidePanelRef?.toggleSidePanel()" :statistics="packagesStats" />

    <SidePanel ref="sidePanelRef" title="Automating steps">
      <form @submit.prevent="submitAutomaticForm" class="flex flex-col gap-2 mb-8 z-10" role="radiogroup"
        aria-label="Automatic options">

        <RadioCard v-for="option in automaticOptions" :key="option.value" :option="option" v-model="selected"
          group-name="automaticOptions" :ref="el => setRadioCardRef(el, option.value)" :tabindex="getTabIndex(option)"
          @radio-keydown="onRadioKeydown" />

        <BaseButton type="submit" class="mt-4" title="Automatic program" styleColor="primary" :isDisabled="!selected"
          :isLoading="globalLoading" />
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

const radioCardRefs = ref({});

const firstEnabledOption = computed(() =>
  automaticOptions.value.find(option => !option.disabled)
);

const enabledOptions = computed(() =>
  automaticOptions.value.filter(option => !option.disabled)
);

const selectedEnabledIndex = computed(() =>
  enabledOptions.value.findIndex(option => option.value === selected.value)
);

function setRadioCardRef(component, value) {
  if (component) {
    radioCardRefs.value[value] = component;
  } else {
    delete radioCardRefs.value[value];
  }
}

function getTabIndex(option) {
  if (option.disabled) return -1;

  // La radio sélectionnée est prioritaire.
  if (selected.value === option.value) return 0;

  // Au chargement, aucune sélection n'est encore définie.
  if (!selected.value && firstEnabledOption.value?.value === option.value) {
    return 0;
  }

  return -1;
}

function focusRadioCard(value) {
  const radioCard = radioCardRefs.value[value];

  if (!radioCard) return;

  radioCard.$el?.focus();
}


function selectAndFocus(value) {
  const option = automaticOptions.value.find(
    option => option.value === value
  );

  if (!option || option.disabled) return;

  selected.value = option.value;

  nextTick(() => {
    focusRadioCard(option.value);
  });
}

function onRadioKeydown(event) {
  // Entrée : exécute le submit du formulaire.
  if (event.key === 'Enter') {
    event.preventDefault();

    if (selected.value && !globalLoading.value) {
      submitAutomaticForm();
    }

    return;
  }

  // Espace : sélectionne la radio actuellement focalisée.
  if (event.key === ' ') {
    event.preventDefault();

    const focusedCard = Object.entries(radioCardRefs.value).find(
      ([, radioCard]) => radioCard?.$el === document.activeElement
    );

    if (focusedCard) {
      selectAndFocus(focusedCard[0]);
    }

    return;
  }

  if (!['ArrowDown', 'ArrowUp', 'ArrowLeft', 'ArrowRight'].includes(event.key)) {
    return;
  }

  event.preventDefault();

  if (!enabledOptions.value.length) return;

  let currentIndex = selectedEnabledIndex.value;

  if (currentIndex === -1) {
    currentIndex = 0;
  }

  const isNext = ['ArrowDown', 'ArrowRight'].includes(event.key);
  const offset = isNext ? 1 : -1;

  const nextIndex =
    (currentIndex + offset + enabledOptions.value.length) %
    enabledOptions.value.length;

  selectAndFocus(enabledOptions.value[nextIndex].value);
}

/* ------------------ Watchers ------------------ */

watch(
  () => automaticOptions.value,
  options => {
    const firstEnabled = options.find(option => !option.disabled);

    if (!firstEnabled) {
      selected.value = null;
      return;
    }

    // La sélection actuelle est-elle encore disponible ?
    const selectedOption = options.find(
      option => option.value === selected.value
    );

    if (!selectedOption || selectedOption.disabled) {
      selected.value = firstEnabled.value;
    }
  },
  {
    immediate: true,
    deep: true,
  }
);

watch(
  () => sidePanelRef.value?.isOpen,
  async isOpen => {
    if (!isOpen) {
      selected.value = null;
      return;
    }

    await nextTick();

    const firstEnabled = firstEnabledOption.value;

    if (firstEnabled) {
      selectAndFocus(firstEnabled.value);
    }
  }
);
</script>