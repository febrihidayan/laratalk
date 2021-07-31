require('./plugins')

import { createVNode, createApp, render } from 'vue'
import App from './App.vue'

import GlobalMixin from './Mixins/GlobalMixin'
import HelperMixin from './Mixins/HelperMixin'
import Modal from './components/modules/Modal.vue'
import BoxAside from './components/modules/BoxAside.vue'

const app = createApp({
    mixins: [
        GlobalMixin,
        HelperMixin
    ],
    components: {
        Modal,
        BoxAside
    },
    ...App
})

const modal = (params) => {

    params = {
        ...params,
        programmatic: true
    }

    const container = document.createElement('div')

    const vm = createVNode(
        Modal,
        params
    )

    render(vm, container)

    document.body.appendChild(container.firstElementChild)

    return vm
}

app.config.globalProperties.$modal = modal

app.mount('#laratalk')