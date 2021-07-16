require('./plugins')

import { createApp } from 'vue'
import App from './App.vue'

createApp(App)
.mixin({
    data: {
        dark_mode: false,
        laratalk: window.laratalk,
        trans: window.laratalk.translations,
    },

    methods: {
        setDarkMode(val = null) {
            let bool = val || this.dark_mode

            bool = typeof bool == 'string' ? JSON.parse(bool) : bool

            if (val == null) {
                bool = !bool
            }

            if (bool) {
                document.documentElement.classList.add('dark')
            }
            else {
                document.documentElement.classList.remove('dark')
            }

            localStorage.setItem('dark_mode', bool)
            this.dark_mode = bool
        }
    },

    mounted() {
        this.setDarkMode(localStorage.getItem('dark_mode'))
    }
})
.mount('#laratalk')