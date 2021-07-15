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
    
    mounted() {
        this.dark_mode = localStorage.getItem('dark_mode')
    },

    watch: {
        dark_mode: function(e) {
            this.dark_mode = e
            localStorage.setItem('dark_mode', e)

            if (e) {
                document.documentElement.classList.add('dark')
            }
            else {
                document.documentElement.classList.remove('dark')
            }
        }
    }
})
.mount('#laratalk')