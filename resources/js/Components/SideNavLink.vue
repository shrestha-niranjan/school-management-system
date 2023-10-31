<script setup>
import { computed, ref } from 'vue'
import { Link } from '@inertiajs/vue3'
import { useLayoutStore } from '@/Store/layout.js'
import BaseIcon from '@/Components/BaseIcon.vue'

const props = defineProps({
  href: {
    type: String
  },
  active: {
    type: Boolean,
    default: false
  },
  label: String,
  icon: String,
  permission: String,
  subMenus: Object
})

const layoutStore = useLayoutStore()

const classes = computed(() =>
  props.active
    ? 'flex cursor-pointer items-center truncate gap-2 bg-slate-600 rounded-md p-1 text-sm text-white outline-none transition duration-300 ease-linear hover:bg-slate-50/20 hover:text-white hover:outline-none dark:text-gray-300 dark:hover:bg-white/10 dark:focus:bg-white/10 dark:active:bg-white/10'
    : 'flex  cursor-pointer items-center truncate gap-2 rounded-md p-1 text-sm text-white outline-none transition duration-300 ease-linear hover:bg-slate-50/20  hover:outline-none dark:text-gray-300 dark:hover:bg-white/10 dark:focus:bg-white/10 dark:active:bg-white/10'
)

const isCollapsed = ref(true)

const subMenuClicked = () => (isCollapsed.value = !isCollapsed.value)
</script>

<template>
  <li
    class="relative w-full"
  >
    <a
      @click="subMenuClicked"
      v-if="subMenus"
      href="#"
      :class="[classes, layoutStore.isSideCollapsed ? 'justify-center' : '']"
    >
      <span>
        <BaseIcon
          v-if="icon"
          :path="icon"
          class="text-primary"
        />
      </span>
      <span
        :class="layoutStore.isSideCollapsed ? 'block sm:hidden ' : 'block'"
      >
        {{ label }}
      </span>
      <span
        v-if="subMenus"
        :class="!layoutStore.isSideCollapsed ? 'ml-auto' : 'hidden'"
        class="absolute  right-0  transition-transform duration-300 ease-linear motion-reduce:transition-none [&>svg]:text-gray-600 dark:[&>svg]:text-gray-300"
      >
        <svg
          :class="!isCollapsed ? 'rotate-180' : ''"
          xmlns="http://www.w3.org/2000/svg"
          viewBox="0 0 20 20"
          fill="currentColor"
          class="h-5 w-5"
        >
          <path
            fill-rule="evenodd"
            d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
            clip-rule="evenodd"
          />
        </svg>
      </span>
    </a>

    <Link
      v-else
      :href="href"
      :class="[classes, layoutStore.isSideCollapsed ? 'justify-center' : '']"
    >
      <span>
         <BaseIcon
           v-if="icon"
           :path="icon"
           class="text-primary"
         />
      </span>
      <span
        :class="
          layoutStore.isSideCollapsed ? 'block ml-2 sm:hidden' : 'block ml-2'
        "
      >
        {{ label }}
      </span>
    </Link>

    <ul
      v-if="subMenus"
      :class="[isCollapsed ? 'hidden transition-position duration-300' : 'block transition-position duration-300', !layoutStore.isSideCollapsed ? 'ml-5' : '']"
      class="relative m-0 list-none p-0  space-y-2"
    >
      <li
        v-for="(menu, index) in subMenus"
        :key="index"
        class="relative"
      >
        <Link
          :href="route(menu.route)"
          :class="[classes, layoutStore.isSideCollapsed ? 'justify-center' : '', route().current(menu.route) ? 'bg-slate-600' : '' ]"
        >
          <span :class="layoutStore.isSideCollapsed ? 'block' : 'block'">
            <BaseIcon
              :path="menu.icon"
              v-if="menu.icon"
            />
          </span>
          <span
            :class="
              layoutStore.isSideCollapsed ? 'block sm:hidden' : 'block ml-2'
            "
          >
            {{ menu.label }}
          </span>
        </Link>
      </li>
    </ul>
  </li>
</template>
