<script setup>
import { ref, computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import * as mdiIcons from '@mdi/js';
import BaseIcon from '@/Components/BaseIcon.vue'
import AsideMenuList from '@/Components/AsideMenuList.vue'
import { useLayoutStore } from '@/Store/layout.js'

// Add itemHref
const itemHref = computed(() =>
  props.item.route ? route(props.item.route) : props.item.href
)

// Add activeInactiveStyle
const activeInactiveStyle = computed(() =>
  props.item.route && route().current(props.item.route) ? 'bg-slate-700' : ''
)

const layoutStore = useLayoutStore()

const props = defineProps({
  item: {
    type: Object,
    required: true
  },
  isDropdownList: Boolean
})


const emit = defineEmits(['menu-click'])

const isDropdownActive = ref(false)

const componentClass = computed(() => [
  props.isDropdownList && !layoutStore.isSideCollapsed ? 'p-1 text-sm' : 'py-1'
])

const hasDropdown = computed(() => !!props.item.menu)

const menuClick = (event) => {
  emit('menu-click', event, props.item)

  if (hasDropdown.value) {
    isDropdownActive.value = !isDropdownActive.value
  }
}
</script>

<template>
  <li
    v-if="item.isVisible"
    class="text-white text-sm w-full rounded"
    :class="[activeInactiveStyle, layoutStore.isSideCollapsed ? 'flex flex-col items-center': '']"
  >
    <component
      :is="item.route ? Link : 'a'"
      :href="itemHref"
      :target="item.target ?? null"
      class="flex cursor-pointer items-center gap-2 "
      :class="[componentClass]"
      :title="!layoutStore.isSideCollapsed  ? item.label : ''"
      @click="menuClick"
    >
      <BaseIcon
        v-if="item.icon"
        :path="mdiIcons[item.icon]"
        class="flex-none"
        :size="18"
      />

      <span
        class="grow text-ellipsis text-sm"
        :class="[
          // { 'pr-12': !hasDropdown },
          {'hidden': layoutStore.isSideCollapsed }
        ]"
      >
        {{ item.label }}
      </span>

      <BaseIcon
        v-if="hasDropdown && !layoutStore.isSideCollapsed"
        :path="isDropdownActive ? mdiIcons['mdiMinus'] : mdiIcons['mdiPlus']"
        class="flex-none"
      />
    </component>

    <AsideMenuList
      v-if="hasDropdown"
      :menu="item.menu"
      :class="[
        useLayoutStore().isSideCollapsed ? 'bg-gray-700/50' : '',
        isDropdownActive ? 'block dark:bg-slate-800/50 ' : 'hidden'
      ]"
      is-dropdown-list
    />
  </li>
</template>
