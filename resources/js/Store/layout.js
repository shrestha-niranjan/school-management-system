import { defineStore } from 'pinia'

export const useLayoutStore = defineStore('layout', {
    state: () => ({
        isSideCollapsed: false
    }),
    actions: {
        setIsSideCollapsed (value) {
            this.isSideCollapsed = value
        },
        toggleIsSideCollapsed () {
            this.isSideCollapsed = !this.isSideCollapsed
        }
    }
})
