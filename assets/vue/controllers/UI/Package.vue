<template>
  <div
    class="w-full bg-white dark:bg-gray-800/50 border border-0 dark:border-1 rounded-md shadow-sm dark:shadow-none dark:border-gray-700/90">
    <dl class="flex flex-col divide-y divide-gray-200 dark:divide-gray-700/90">
      <div class="flex justify-between p-4">
        <div class="flex flex-col">
          <dt class="bjao bjat bjbe">Id</dt>
          <dd class="bivj bjal bjat bjbe">{{ package.id }}</dd>
        </div>
        <div class="self-end">
          <BadgeComponent type="warning" :title="package.location" />
        </div>
      </div>
      <div class="p-4">
        <div class="flex">
          <dt class="biww">
            <AccountCircleIcon color="text-gray-200 dark:text-gray-700/90" title="client" />
          </dt>
          <dd class="bjao bjar bjbe">Alex Curren</dd>
        </div>
        <div class="bivm bivu biwp biww bixn bizm">
          <dt class="biww">
            <span class="biul">Due date</span>
            <AccountCircleIcon color="text-gray-200 dark:text-gray-700/90" title="client" />
          </dt>
          <dd class="bjao bjaw">
            <time datetime="2023-01-31">January 31, 2023</time>
          </dd>
        </div>
      </div>
    </dl>
    <div class="border-t border-gray-200 dark:border-gray-700/90 p-4">
      <a href="#" class="bjao bjat bjbe">Download receipt <span aria-hidden="true">→</span></a>
    </div>
  </div>
</template>

<script setup>
import { onMounted } from 'vue';
import BadgeComponent from './BadgeComponent.vue';
import AccountCircleIcon from './Icons/AccountCircleIcon.vue';

const props = defineProps({
  package: Object
});
onMounted(() => {
  console.log('package', props.package);

})
</script>
