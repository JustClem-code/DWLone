<template>
  <div class="flex flex-col gap-2">
    <StatsHeader title="Packages statistics" notice="You can automate the steps" actionTitle="Automating steps"
      @actionClick="sidePanelRef?.toggleSidePanel()" :statistics="packagesStats" />

    <SidePanel ref="sidePanelRef" title="Automating steps">

      <RadioForm :options="automaticOptions" v-model="selected" :isLoading="globalLoading"
        @submitForm="submitAutomaticForm" :sidePanelRef="sidePanelRef" />

    </SidePanel>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { usePostFetch } from '../../composables/fetch.js';
import { useNotification } from '../../composables/eventBus.js';
import { dashboardStore } from '../../composables/dashboardStore.js';

import SidePanel from '../UI/SidePanel.vue';
import StatsHeader from './StatsHeader.vue';
import RadioForm from '../UI/RadioForm.vue';

const { allPackagesStats, updateDashboardData } = dashboardStore();
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

const submitAutomaticForm = () => {
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

</script>