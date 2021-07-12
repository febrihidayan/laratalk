require('./plugins')

import { createApp } from 'vue'
import App from './App.vue'

createApp(App)
.mixin({
    data: {
        laratalk: window.laratalk
    }
})
.mount('#laratalk')