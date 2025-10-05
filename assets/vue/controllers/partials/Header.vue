<template>
  <!-- <Sidebar bind:open bind:is_user /> -->
  <nav class="flex items-center justify-between flex-wrap bg-white p-6 border-b border-solid border-gray-200">
    <!-- <LogoTitle /> -->
    <div class="block md:hidden">
      <button
        class="flex items-center px-3 py-2 border rounded text-gray-200 border-gray-400 hover:text-white hover:border-white"
        @click="() => (open = !open)">
        <svg class="fill-current h-3 w-3" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
          <title>Menu</title>
          <path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z" />
        </svg>
      </button>
    </div>
    <div class="w-full flex-grow md:flex md:items-center md:w-auto hidden md:block">
      <NavContent :navigation="navigation" :current-item="currentItem"/>
    </div>
  </nav>
  <header class="relative bg-gray-50">
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
      <h1 class="text-3xl font-bold tracking-tight text-gray-900">{{ currentItem.name }}</h1>
    </div>
  </header>
</template>

<script setup>
import { ref, computed, watchEffect, onMounted } from 'vue'
import NavContent from './NavContent.vue';
/* import Sidebar from "./Sidebar.svelte";
import LogoTitle from "./LogoTitle.svelte";

export let is_user;

let open = false;

$: open
    ? (document.body.style.position = "fixed")
    : (document.body.style.position = ""); */

const open = ref(false)

const navigation = ref([
  { name: 'Dashboard', href: '/' },
  { name: 'Yard Truck', href: '/yard/truck' },
  { name: 'Induction', href: '#' },
])

const currentItem = ref(navigation[0])

function updateCurrentItem() {
  const path = window.location.pathname
  const found = navigation.value.find(item => item.href === path)
  currentItem.value = found || navigation[0] // fallback to first if not found
}

// Initial set
updateCurrentItem()

// Handle browser navigation (back/forward)
window.addEventListener('popstate', () => {
  updateCurrentItem()
})

</script>
