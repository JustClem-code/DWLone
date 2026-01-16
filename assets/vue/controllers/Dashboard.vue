<template>
  <div class="flex flex-col gap-8">
    <BorderedContent title="Welcome">
      <div>
        <h2 class="text-2xl">
          Hello {{ userName ?? 'John Doe' }}!
        </h2>
      </div>
    </BorderedContent>
    <BorderedContent title="Bags">
      <div class="grid grid-cols-6 gap-4">
        <HorizontalLinkButton v-for="(location) in locations" :key="location.id" @click="setCurrentBag(location.bag)"
          :title="location.name" :focused="location.bag?.packages?.length > 0 ? 'text-red-500' : ''" />
      </div>
    </BorderedContent>

    <DialogComponentSlot ref="infoDialogRef" :hasCloseCross="true">
      <BagInfo :currentBag="currentBag" />
    </DialogComponentSlot>
  </div>
</template>

<script setup>

//TODO:

// package drop id est vide dans le header Stow
// calculer le poids du sac
// Filtrer en fonctions des locations vides
// créer un script d'induction automatique et de stow automatique

// WIP : Revoir les method de repository to array pour limiter les données inutiles ou dupliquée
// créer un vignette Bag sur le dashboard
// afficher les bags dans la vignette

// revoir la gestion de la mise en Bag, regrouper les Bags des routes dans la même allée si possible donc des routes
// grouper en fonctions des postcode

// régler les problèmes ERROR lens (package...)
// gestion du animate pulse si chargement de la (list / HorizontalLinkButton et divers composants) => voir tailwind UI
// plus globalement, il faut gérer le chargement côté vue et twig

import { onMounted, watch, ref, provide, computed } from 'vue'
import BorderedContent from './UI/BorderedContent.vue';

import { userStore } from '../composables/userStore.js'
import { useFetch } from '../composables/fetch.js'
import HorizontalLinkButton from './UI/Buttons/HorizontalLinkButton.vue';
import DialogComponentSlot from './UI/Modals/DialogComponentSlot.vue';
import BagInfo from './DashboardComponents/BagInfo.vue';

const props = defineProps({
  is_user: Boolean,
  user_name: String
});

const { userName } = userStore()

const { data: locations, error: errorLocations } = useFetch('/getBagsInLocations')

onMounted(() => {
  console.log(`the component is now mounted.`)
})

const currentBag = ref(null)
const infoDialogRef = ref(null)

const setCurrentBag = (bag) => {
  if (!bag) {
    return
  }

  currentBag.value = bag;
  infoDialogRef.value?.openDialog()
}

</script>
