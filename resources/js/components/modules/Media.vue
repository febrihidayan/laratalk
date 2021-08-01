<template>
    <div 
        :class="[`media flex h-18 dark:divide-gray-500`, {
            'cursor-pointer hover:bg-light-300 dark:hover:bg-true-gray-700': cursor
        }]"
    >
        <div
            v-if="hasLeft"
            class="flex flex-col justify-center px-3"
        >
            <slot name="left" />
        </div>
        <div class="flex flex-grow flex-col justify-center w-73 pr-3">
            <slot />
        </div>
    </div>
</template>

<script>
export default {
    props: {
        cursor: {
            type: Boolean,
            default: true
        }
    },
    computed: {
        hasLeft() {
            return !!this.$slots.left
        }
    }
}
</script>

<style scoped>
.media + .media {
    @apply divide-y;
}
</style>