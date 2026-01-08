<template>
  <div class="flex flex-col gap-4">

    <PackagesStackHeader title="Package Drop" :packagesStack="packages" :notice="noticeHeader" :menuItems="null"
      :actions="null" :numberOfPackages="packages.length" :backButton="true" @backClick="setCurrentPair(null)" />

    <div class="relative w-full min-h-70 flex items-center justify-center">
      <transition name="fade-slide" tag="div" enter-active-class="transition-all duration-500 ease-out"
        enter-from-class="opacity-0" enter-to-class="opacity-100">
        <div v-if="packages.length !== 0" class="absolute top-0 w-full z-10 cursor-pointer"
          v-on:click="setCurrentPackage(packages[0])">
          <Package :package="packages[0]" :borderColor="currentPackage ? 'border-blue-500' : ''" />
        </div>
      </transition>
      <!-- <p v-if="inductPackageLoading" class="animate-pulse">Wait for another package</p> -->
    </div>
  </div>
</template>

<script setup>
import { inject, computed, onMounted } from 'vue'
import Package from '../UI/Package.vue';
import PackagesStackHeader from '../SharedComponents/PackagesStackHeader.vue';

const props = defineProps({
  packages: Array,
});

const noticeHeader = computed(() => {
  return 'Stow the package'
})

const { setCurrentPair, setCurrentPackage, currentPackage } = inject('stow')
</script>
