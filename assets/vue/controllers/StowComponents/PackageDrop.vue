<template>
  <div class="flex flex-col gap-4">

    <PackagesStackHeader title="Package Drop" :packagesStack="pairPackages" :notice="noticeHeader" :menuItems="null"
      :actions="null" :numberOfPackages="pairPackages.packages.length" />

    <div class="relative w-full min-h-70 flex items-center justify-center">
      <transition v-if="!stowingIsLoading" name="fade-slide" tag="div" enter-active-class="transition-all duration-500 ease-out"
        enter-from-class="opacity-0" enter-to-class="opacity-100">
        <div v-if="pairPackages.packages.length !== 0" class="absolute top-0 w-full z-10 cursor-pointer"
          v-on:click="setCurrentPackage(pairPackages.packages[0])">
          <Package :package="pairPackages.packages[0]" :borderColor="currentPackage ? 'border-blue-500' : ''" />
        </div>
      </transition>
      <p v-else  class="animate-pulse">Wait for another package</p>
    </div>
  </div>
</template>

<script setup>
import { inject, computed } from 'vue'
import Package from '../UI/Package.vue';
import PackagesStackHeader from '../SharedComponents/PackagesStackHeader.vue';

const props = defineProps({
  pairPackages: Object,
});

const noticeHeader = computed(() => {
  return props.pairPackages.packages.length === 0 ? 'The drop is empty' : 'Click on the package, then on its location.'
})

const { setCurrentPackage, currentPackage, stowingIsLoading } = inject('stow')
</script>
