import { defineStore } from 'pinia'

export const useStyleStore = defineStore('style', {
    state: () => ({
        darkMode: false
    }),
    actions: {
        setDarkMode (payload = null) {
            this.darkMode = payload !== null ? payload : !this.darkMode

            if (typeof localStorage !== 'undefined') {
                localStorage.setItem('darkMode', this.darkMode ? '1' : '0')
            }

            if (typeof document !== 'undefined') {
                document.body.classList[this.darkMode ? 'add' : 'remove'](
                    'is-dark'
                )

                document.documentElement.classList[
                    this.darkMode ? 'add' : 'remove'
                ]('is-dark')
            }
        }
    }
})
