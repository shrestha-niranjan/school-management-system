<script setup>
import { ref } from 'vue'

defineProps({
  tabs: {
    type: Array
  }
})

const activeTab = ref(0)

const tabClasses = (tab) => {
  return [
    'tracking-wide p-2 w-full',
    tab === activeTab.value ? ' text-emerald-600' : 'text-gray-500'
  ]
}

const tabContentClasses = (tab) => {
  return [
    'opacity-0',
    'transition-opacity',
    'duration-150',
    'ease-linear',
    tab === activeTab.value ? 'opacity-100 data-[te-tab-active]:block' : 'hidden'
  ]
}

const tabLiActive = (tab) => {
  return [
    tab === activeTab.value ? 'border-gray-300 border-t-2 bg-gray-100 sm:bg-inherit border-l-2 border-r-2 text-center rounded-t py-2 duration-200' : 'sm:border-b-2 border-gray-300 duration-200  text-center py-2'
  ]
}


const setActiveTab = (index) => {
  activeTab.value = index
}
</script>
<template>
  <div class="w-full">
    <ul class="flex list-none flex-col sm:flex-row" role="tablist" data-te-nav-ref>
      <li
        role="presentation"
        :class="tabLiActive(index)"
        class="hover:bg-gray-100"
        v-for="(tab, index) in tabs"
        :key="index"
      >
        <a
          :href="'#tabs-' + index"
          :class="tabClasses(index)"
          data-te-toggle="pill"
          :data-te-target="'#tabs-' + index"
          :data-te-nav-active="index === activeTab"
          role="tab"
          :aria-controls="'tabs-' + index"
          :aria-selected="index === activeTab"
          @click.prevent="setActiveTab(index)"
        >
          {{ tab.label }}
        </a>
      </li>
    </ul>

    <!-- Tabs content -->
    <div class="pt-4">
      <div
        :class="tabContentClasses(index)"
        :id="'tabs-' + index"
        role="tabpanel"
        :aria-labelledby="'tabs-' + index"
        :data-te-tab-active="index === activeTab"
        v-for="(tab, index) in tabs"
        :key="index"
      >
        <slot name="tab-content" :activeValue="tab.value">

        </slot>
      </div>
    </div>
  </div>
</template>


