<template>
  <h2 class="text-7xl text-lapis font-bold underline"> Home controller in Vue</h2>
  <div>Hello {{ user_name }}!</div>

  <div>
    <div v-if="data">
      <div v-for="dat in data" key="dat.id">
        <p>{{ dat.name }} {{ dat.truckId }}</p>
      </div>
    </div>
    <div v-else-if="error">Error: {{ error }}</div>
    <div v-else>Loading...</div>
  </div>
</template>

<script setup>
import { useFetch } from './fetch.js'
import { onMounted, ref } from 'vue'
defineProps({
  user_name: String
});

const { data, error } = useFetch('/getdocks')

/* const data = ref(null);
const error = ref(null); */

/* const fetchData = async () => {
  try {
    const response = await fetch('/getdocks');
    data.value = await response.json();
  } catch (err) {
    error.value = 'erreur';
  }
}; */

onMounted(() => {
  console.log(`the component is now mounted.`)
  /* fetchData(); */
})

</script>
