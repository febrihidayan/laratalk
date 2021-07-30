<template>
    <transition name="zoom-out">
        <div v-show="isActive" class="fixed z-100 inset-0 overflow-y-auto">
            <div class="flex items-end flex-col justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div
                    :class="[`fixed inset-0 bg-light-50 dark:bg-dark-50 !bg-opacity-75 transition-opacity`, {
                        'opacity-0': !isActive
                    }]"
                />
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                <div
                    :class="[`inline-block align-bottom bg-white dark:bg-dark-50 rounded-lg text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full`, {
                        'opacity-0': !isActive
                    }]"
                >
                    <!-- modal content -->
                    <div class="bg-white dark:bg-dark-300 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <slot/>
                            </div>
                        </div>
                    </div>
        
                    <!-- modal footer -->
                    <div class="bg-gray-50 dark:bg-dark-400 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button
                            type="button"
                            @click="success"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm"
                        >{{
                            trans.ok
                        }}</button>
                        <button
                            v-if="onCancel"
                            type="button"
                            @click="isActive=false"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                        >{{
                            trans.cancel
                        }}</button>
                    </div>
                </div>
            </div>
        </div>
    </transition>
</template>

<script>
export default {
    props: {
        modelValue: Boolean,
        onCancel: {
            type: Boolean,
            default: true
        }
    },
    data() {
        return {
            isActive: this.modelValue || false,
            trans: window.laratalk.translations
        }
    },
    watch: {
        modelValue(value) {
            this.isActive = value
        },

        isActive(value) {
            this.$emit('update:modelValue', value)
        }
    },
    methods: {
        success() {
            this.isActive = false
            this.$emit('success', true)
        }
    }
}
</script>