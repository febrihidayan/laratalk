require('./plugins')

import { createApp } from 'vue'
import App from './App.vue'

import GlobalMixin from './Mixins/GlobalMixin'
import HelperMixin from './Mixins/HelperMixin'

createApp({
    mixins: [
        GlobalMixin,
        HelperMixin
    ],
    ...App
})
.mount('#laratalk')