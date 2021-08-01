require('./plugins')

import { createVNode, createApp, render } from 'vue'
import App from './App.vue'

import GlobalMixin from './Mixins/GlobalMixin'
import HelperMixin from './Mixins/HelperMixin'
import Modal from './components/modules/Modal.vue'
import BoxAside from './components/modules/BoxAside.vue'

import {
    ChevronLeftIcon,
    ChevronRightIcon,
    LogoutIcon,
    SunIcon
} from '@heroicons/vue/outline'

import {
    AnnotationIcon,
    CheckIcon,
    ChevronDownIcon,
    DotsVerticalIcon,
    MoonIcon,
    SearchIcon,
    TranslateIcon,
    TrashIcon,
    XIcon
} from '@heroicons/vue/solid'

const app = createApp({
    mixins: [
        GlobalMixin,
        HelperMixin
    ],
    components: {
        Modal,
        BoxAside,

        // icons
        AnnotationIcon,
        CheckIcon,
        ChevronDownIcon,
        ChevronLeftIcon,
        ChevronRightIcon,
        DotsVerticalIcon,
        LogoutIcon,
        MoonIcon,
        SearchIcon,
        SunIcon,
        TranslateIcon,
        TrashIcon,
        XIcon
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