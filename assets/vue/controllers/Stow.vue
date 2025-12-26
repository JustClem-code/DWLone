<template>
  <div class="flex flex-col gap-8">
    <BorderedContent title="Location">
      <h2>Hello stower</h2>
    </BorderedContent>
  </div>
</template>

<script setup>

import { ref, provide, computed } from 'vue'
import BorderedContent from './UI/BorderedContent.vue'

import { useFetch, usePostFetch } from '../composables/fetch.js'
import { useNotification } from '../composables/eventBus.js'

const { data: docks, error: errorDock } = useFetch('/getdocks')

const { notifier } = useNotification()

const dockingData = ref(null)
const dockingError = ref(null)
const dockingIsLoading = ref(false)

provide('yardTruck', {  })

</script>
