<template>
    <aside
        :class="[`bg-white dark:bg-dark-300 overflow-hidden fixed top-0 left-0 z-40`, {
            'w-0': !isActive,
            '<sm:w-full <md:w-100': isActive,
            '<xl:w-35/100 xl:w-3/10': !$root.isAsideRight && isActive,
            '<lg:w-35/100 <xl:w-3/10 xl:w-25/100': $root.isAsideRight && isActive
        }]"
    >
        <div class="sm:border-r-1 dark:sm:border-dark-200">
            <div class="flex bg-blue-500 dark:bg-dark-400 text-white">
                <a class="cursor-pointer p-4" @click="isActive=false">
                    <ChevronLeftIcon
                        class="svg-icon !text-white"
                    />
                </a>
                <p class="text-base my-4">{{
                    name
                }}</p>
            </div>
            <div class="flex flex-col bg-white dark:bg-dark-200 h-full">
                <slot />
            </div>
        </div>
    </aside>
</template>

<script>
import { ChevronLeftIcon } from '@heroicons/vue/outline'

export default {
    props: {
        modelValue: Boolean,
        name: String
    },
    components: { ChevronLeftIcon },
    data() {
        return {
            isActive: this.modelValue || false
        }
    },
    watch: {
        modelValue(value) {
            this.isActive = value
        },

        isActive(value) {
            this.$emit('update:modelValue', value)
        }
    }
}
</script>