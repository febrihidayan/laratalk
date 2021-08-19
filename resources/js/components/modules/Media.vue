<template>
    <div 
        :class="[`media flex cursor-pointer hover:bg-light-300 dark:hover:bg-true-gray-700 h-18`, {
            'hover:bg-light-300 dark:hover:bg-true-gray-700': !isActive,
            'bg-light-500 dark:bg-true-gray-800': isActive
        }]"
        v-on:click="checkable ? activeChecked=!activeChecked : ''"
    >
        <label
            v-if="checkable"
            class="flex justify-center cursor-pointer items-center ml-4 mr-1"
        >
            <input
                v-model="activeChecked"
                type="checkbox"
                class="form-checkbox h-4 w-4 text-violet-500 cursor-pointer"
                :checked="activeChecked"
            >
        </label>
        <div
            v-if="hasLeft || avatar"
            class="flex flex-col justify-center px-3"
        >
            <Avatar
                v-if="avatar"
                :image="avatar"
                :size="10"
            />
            <slot v-else name="left" />
        </div>
        <div class="flex flex-grow flex-col justify-center w-0 pr-3">
            <slot />
        </div>
    </div>
</template>

<script>
import Avatar from './Avatar.vue'

export default {
    props: {
        avatar: String,
        checkable: {
            type: Boolean,
            default: false
        },
        isActive: {
            type: Boolean,
            default: false
        },
        isChecked: Boolean
    },
    components: { Avatar },
    computed: {
        hasLeft() {
            return !!this.$slots.left
        }
    },
    data() {
        return {
            activeChecked: this.isChecked || false
        }
    },
    watch: {
        activeChecked(bool) {
            this.$emit('onChecked', bool)
        }
    }
}
</script>

<style scoped>
.media + .media {
    @apply divide-y dark:divide-gray-500;
}
</style>