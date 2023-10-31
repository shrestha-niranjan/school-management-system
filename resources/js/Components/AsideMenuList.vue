<script setup>
import AsideMenuItem from '@/Components/AsideMenuItem.vue'
import { useLayoutStore } from '@/Store/layout.js'

defineProps({
  isDropdownList: Boolean,
  menu: {
    type: Array,
    required: true
  }
})

const emit = defineEmits(['menu-click'])

const menuClick = (event, item) => {
  emit('menu-click', event, item)
}
</script>

<template>
  <ul class="w-full space-y-2 mt-2"
      :class="useLayoutStore().isSideCollapsed ? 'pl-0 ' : 'pl-3'"
  >
    <AsideMenuItem
      v-for="(item, index) in menu"
      :key="index"
      :item="item"
      :is-dropdown-list="isDropdownList"
      @menu-click="menuClick"
    />
  </ul>
</template>
