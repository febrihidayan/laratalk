<template>
    <aside
        :class="[`bg-white h-full dark:bg-dark-300 sm:border-r-1 dark:sm:border-dark-200 fixed top-0 left-0 z-30 ease-in-out transition-all duration-300`, {
            'min-w-100 <sm:w-full': isActive,
            '-left-full -left-100': !isActive
        }]"
    >
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