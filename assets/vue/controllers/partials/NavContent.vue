<template>
  <div class="text-sm md:flex-grow">
    <a v-for="item in navigation" :key="item.name" :href="item.href"
      :class="[item === currentItem ? 'bg-gray-900 text-white' : 'text-gray-700 hover:bg-white/5 hover:text-gray-800', 'rounded-md px-3 py-2 text-sm font-medium']"
      :aria-current="item.current ? 'page' : undefined">{{ item.name }}</a>

    <!-- <a href="/" class="block mt-4 md:inline-block md:mt-0 text-gray-200 hover:text-white mr-4">
      Dashboard
    </a> -->
  </div>
</template>

<script setup>

const props = defineProps({
  navigation: Array,
  currentItem: Object
})

</script>
