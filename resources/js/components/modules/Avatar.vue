<template>
    <figure class="relative">
        <div
            v-if="!src"
            class="flex justify-center items-center bg-dark-50 rounded-full"
            v-bind:style="whStyle"
        >
            <UserGroupIcon
                v-if="isIconGroup"
                class="svg-icon !text-gray-300"
                v-bind:style="whIconStyle"
            />
            <UserIcon
                v-else
                class="svg-icon !text-gray-300"
                v-bind:style="whIconStyle"
            />
        </div>
        <img
            v-else
            class="rounded-full"
            v-bind:style="whStyle"
            :src="src"
            alt="avatar"
        >

        <label
            v-if="isInput && (isIconGroup || !config.user_gravatar)"
            @mouseover="isHover=true"
            @mouseleave="isHover=false"
            class="absolute rounded-full"
            v-bind:style="whStyle"
        >
            <div
                v-if="isHover"
                class="flex flex-col text-white inline-block justify-center items-center rounded-full cursor-pointer bg-opacity-75 bg-dark-50"
                v-bind:style="whStyle"
            >
                <CameraIcon
                    class="svg-icon"
                />
            </div>
            <input
                type="file"
                @change="onFileChange"
                :accept="acceptImage"
                class="hidden"
            >
        </label>
    </figure>
</template>

<script>
import { mapGetters } from 'vuex'

import {
    CameraIcon
} from '@heroicons/vue/outline'

import {
    UserGroupIcon,
    UserIcon
} from '@heroicons/vue/solid'

export default {
    props: {
        image: String,
        isIconGroup: {
            type: Boolean,
            default: false
        },
        isInput: {
            type: Boolean,
            default: false
        },
        isUpload: {
            type: Boolean,
            default: false
        },
        modelValue: [String, Object],
        size: {
            type: Number,
            default: 10
        },
        userId: {
            type: [String, Number],
            default: null
        },
        userType: {
            type: String,
            default: null
        }
    },
    components: {
        CameraIcon,
        UserGroupIcon,
        UserIcon
    },
    computed: {
        ...mapGetters({
            auth_user: 'config/profile',
            config: 'config/config',
        }),

        acceptImage() {
            return 'image/' + this.config.storage_image_format.join(',image/')
        },

        whStyle() {
            return this.styleObject(this.size)
        },

        whIconStyle() {
            return this.styleObject(this.size/2)
        }
    },
    data() {
        return {
            src: this.image || null,
            isHover: false
        }
    },
    methods: {
        onFileChange(e)
        {
            const file = e.target.files[0]
            this.src = URL.createObjectURL(file)

            this.$emit('update:modelValue', file)
            
            if (this.isUpload && (this.isIconGroup || !this.config.user_gravatar)) {
                const data = new FormData()
                data.append('image', file)

                if (this.userId && this.userType) {
                    data.append('user_id', this.userId)
                    data.append('user_type', this.userType)
                }

                axios
                    .post('upload-avatar', data)
                    .then(({ data }) => {
                        if (!this.userId && !this.userType) {
                            
                            this.auth_user.avatar = data.path

                        }

                        this.$emit('changed', {
                            path: data.path,
                            id: this.userId,
                            type: this.userType
                        })
                    })
            }
        },
        
        styleObject(size)
        {
            size = size * 0.25
            
            return {
                height: `${size}rem`,
                width: `${size}rem`
            }
        }
    },
    watch: {
        image: function(e) {
            this.src = e
        }
    }
}
</script>