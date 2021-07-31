<template>
    <aside
        :class="[`bg-white h-full dark:bg-dark-300 sm:border-r-1 dark:sm:border-dark-200 fixed top-0 left-0 z-30 ease-in-out transition-all duration-300`, {
            'min-w-100 <sm:w-full': isActive,
            '-left-full -left-100': !isActive
        }]"
    >
        <div class="flex bg-blue-500 dark:bg-dark-400 text-white p-4">
            <a class="cursor-pointer" @click="isActive=false">
                <svg class="svg-icon !fill-white !stroke-white" viewBox="0 0 20 20">
                    <path d="M8.388,10.049l4.76-4.873c0.303-0.31,0.297-0.804-0.012-1.105c-0.309-0.304-0.803-0.293-1.105,0.012L6.726,9.516c-0.303,0.31-0.296,0.805,0.012,1.105l5.433,5.307c0.152,0.148,0.35,0.223,0.547,0.223c0.203,0,0.406-0.08,0.559-0.236c0.303-0.309,0.295-0.803-0.012-1.104L8.388,10.049z"></path>
                </svg>
            </a>
            <p class="text-base ml-3">{{
                name
            }}</p>
        </div>
        <div class="flex flex-col bg-white dark:bg-dark-200 h-full">
            <slot />
        </div>
    </aside>
</template>

<script>
export default {
    props: {
        modelValue: Boolean,
        name: String
    },
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