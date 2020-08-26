import Vue from 'vue'
import App from './App.vue'

require('./plugins')

Vue.config.productionTip = false

const app = new Vue({
    el: '#laratalk',
    render: h => h(App),
})