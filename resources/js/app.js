require('./plugins')

import { createVNode, createApp, render } from 'vue'
import { useStore } from 'vuex'
import store from './store'
import App from './App.vue'

/**
 * Mixins
 */
import GlobalMixin from './mixins/GlobalMixin'

/**
 * Components
 */
import Avatar from './components/modules/Avatar.vue'
import BoxAside from './components/modules/BoxAside.vue'
import Media from './components/modules/Media.vue'
import Modal from './components/modules/Modal.vue'

/**
 * Svg icons
 */
import {
    CheckIcon,
    ChevronLeftIcon,
    ChevronRightIcon,
    LogoutIcon,
    SunIcon,
    TranslateIcon
} from '@heroicons/vue/outline'

import {
    AnnotationIcon,
    ChevronDownIcon,
    DotsVerticalIcon,
    MoonIcon,
    SearchIcon,
    TrashIcon,
    XIcon
} from '@heroicons/vue/solid'

const app = createApp({
    mixins: [
        GlobalMixin,
    ],
    components: {
        Avatar,
        BoxAside,
        Media,
        Modal,

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
    setup() {
        const store = useStore()

        store.dispatch('config/fetchConfig')
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

app.use(store)

app.mount('#laratalk')