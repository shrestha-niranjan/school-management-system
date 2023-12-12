<script setup>
import { ref } from 'vue'
import ApplicationLogo from '@/Components/ApplicationLogo.vue'
import Dropdown from '@/Components/Dropdown.vue'
import DropdownLink from '@/Components/DropdownLink.vue'
import { Link, usePage } from '@inertiajs/vue3'
import {
    mdiBackburger,
    mdiWhiteBalanceSunny,
    mdiWeatherNight,
    mdiForwardburger
} from '@mdi/js'
import BaseIcon from '@/Components/BaseIcon.vue'
import { useLayoutStore } from '@/Store/layout.js'
import { useStyleStore } from '@/Store/style.js'
import AsideMenuList from '@/Components/AsideMenuList.vue'

const showingNavigationDropdown = ref(false)

const layoutStore = useLayoutStore()

const onHamClick = () => {
    showingNavigationDropdown.value = !showingNavigationDropdown.value
    layoutStore.setIsSideCollapsed(false)
}

const toggleDarkMode = () => {
    useStyleStore().setDarkMode()
}
</script>

<template>
    <Toast />

    <ConfirmDialog></ConfirmDialog>

    <div :class="['w-full h-screen', { dark: !useStyleStore().darkMode }]">
        <div
            class="max-w-[128rem] container bg-gray-50 dark:bg-slate-700 px-2 mx-auto"
        >
            <aside
                :class="[
                    layoutStore.isSideCollapsed ? 'w-24' : 'w-60',
                    showingNavigationDropdown
                        ? 'translate-x-0'
                        : '-translate-x-full'
                ]"
                class="fixed left-0 top-0 z-40 h-screen overflow-y-auto sm:translate-x-0 transition-position duration-500 bg-gray-400/40 dark:bg-gray-800 divide-y-2 space-y-2"
                tabindex="-1"
                aria-labelledby="drawer-label"
            >
                <div class="w-full h-screen py-6 bg-slate-800">
                    <div
                        id="sidenavbar"
                        :class="
                            layoutStore.isSideCollapsed ? 'items-center' : ''
                        "
                        class="space-y-4 flex flex-col px-1 w-full items-center overflow-y-scroll"
                    >
                        <ul class="w-full space-y-2">
                            <li class="w-full flex justify-center">
                                <Link
                                    :href="route('dashboard')"
                                    class="flex items-center gap-3"
                                >
                                    <ApplicationLogo
                                        class="block h-10 w-auto"
                                    />
                                    <p
                                        v-if="!useLayoutStore().isSideCollapsed"
                                        class="text-white text-xl font-bold tracking-wider"
                                    >
                                        {{
                                            usePage().props.settingData &&
                                            usePage().props.settingData.name
                                                ? usePage().props.settingData
                                                      .name
                                                : 'School MS'
                                        }}
                                    </p>
                                </Link>
                            </li>
                        </ul>

                        <AsideMenuList :menu="usePage().props.menus" />
                    </div>
                </div>
            </aside>

            <div
                :class="[
                    layoutStore.isSideCollapsed
                        ? 'pl-0 sm:pl-24'
                        : 'sm:pl-60 pl-0',
                    !showingNavigationDropdown ? 'pl-0 ' : ''
                ]"
                class="h-full transition-position duration-500"
            >
                <div class="flex justify-between h-20 px-2 lg:px-2 py-2">
                    <div
                        class="w-full flex justify-between px-2 h-auto items-center"
                    >
                        <Link :href="route('dashboard')" class="sm:hidden">
                            <ApplicationLogo
                                class="block h-10 w-auto fill-current text-gray-800"
                            />
                        </Link>
                        <BaseIcon
                            @click="layoutStore.toggleIsSideCollapsed()"
                            :path="
                                layoutStore.isSideCollapsed
                                    ? mdiForwardburger
                                    : mdiBackburger
                            "
                            size="25"
                            class="transition-position duration-500 dark:text-white hidden sm:flex cursor-pointer"
                        />
                        <div class="flex gap-2">
                            <Dropdown align="right" width="48">
                                <template #trigger>
                                    <span class="inline-flex rounded-md">
                                        <button
                                            type="button"
                                            class="inline-flex items-center px-3 py-2 border border-transparent dark:text-white text-sm leading-4 font-medium rounded-md text-gray-500 hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
                                        >
                                            {{ $page.props.auth.user.name }}

                                            <svg
                                                class="ml-2 -mr-0.5 h-4 w-4"
                                                xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20"
                                                fill="currentColor"
                                            >
                                                <path
                                                    fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd"
                                                />
                                            </svg>
                                        </button>
                                    </span>
                                </template>

                                <template #content>
                                    <DropdownLink
                                        :href="route('settings.edit')"
                                    >
                                        School Settings
                                    </DropdownLink>

                                    <DropdownLink
                                        :href="route('logout')"
                                        method="post"
                                        as="button"
                                    >
                                        Log Out
                                    </DropdownLink>
                                </template>
                            </Dropdown>
                        </div>

                        <!-- Hamburger -->
                        <div class="flex sm:hidden items-center">
                            <button
                                @click="onHamClick"
                                class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out"
                            >
                                <svg
                                    class="h-6 w-6"
                                    stroke="currentColor"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        v-if="!showingNavigationDropdown"
                                        class="inline-flex"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                    <path
                                        v-else
                                        class="inline-flex"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                <main class="min-h-[85vh]">
                    <div
                        v-if="$page.props.breadcrumbs.items"
                        class="w-full mx-auto sm:px-6 lg:px-8 space-y-4 mb-4"
                    >
                        <Breadcrumb
                            :home="$page.props.breadcrumbs.home"
                            :model="$page.props.breadcrumbs.items"
                        >
                            <template #item="{ label, item, props }">
                                <Link v-if="item.route" :href="item.route">
                                    <span v-bind="props.icon" />
                                    <span v-bind="props.label">{{
                                        label
                                    }}</span>
                                </Link>

                                <a
                                    v-else
                                    :href="item.url"
                                    :target="item.target"
                                    v-bind="props.action"
                                >
                                    <span
                                        v-if="item.icon"
                                        v-bind="props.icon"
                                    />
                                    <span v-bind="props.label">{{
                                        label
                                    }}</span>
                                </a>
                            </template>
                        </Breadcrumb>
                    </div>

                    <slot />
                </main>
                <footer>
                    <div
                        class="py-4 text-center bg-gray-50 dark:bg-slate-700 dark:text-white"
                    >
                        &copy; Made with
                        <span class="animate-pulse">❤️</span> by
                        <a
                            href="https://shresthaniranjan.com.np"
                            target="_blank"
                            class="font-bold hover:underline"
                        >
                            Niranjans
                        </a>
                        @ {{ new Date().getFullYear() }}
                    </div>
                </footer>
            </div>
        </div>
    </div>
</template>
<style scoped>
::-webkit-scrollbar {
    width: 5px;
}
</style>
