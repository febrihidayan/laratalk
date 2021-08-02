<template>
    <figure>
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
            v-if="isUpload"
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
                class="hidden"
            >
        </label>
    </figure>
</template>

<script>
import {
    CameraIcon
} from '@heroicons/vue/outline'

import {
    UserGroupIcon,
    UserIcon
} from '@heroicons/vue/solid'

export default {
    props: {
        hover: Boolean,
        image: String,
        isIconGroup: {
            type: Boolean,
            default: false
        },
        isUpload: {
            type: Boolean,
            default: false
        },
        size: {
            type: Number,
            default: 10
        },
        user: Object
    },
    components: {
        CameraIcon,
        UserGroupIcon,
        UserIcon
    },
    computed: {
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
            isHover: this.hover || false
        }
    },
    methods: {
        onFileChange(e)
        {
            const file = e.target.files[0]
            this.src = URL.createObjectURL(file)

            const data = new FormData()
            data.append('image', file)

            axios
                .post('upload-avatar', data)
                .then(({ data }) => {
                    this.laratalk.profile.avatar = data
                })
        },
        
        styleObject(size)
        {
            size = size * 0.25
            
            return {
                height: `${size}rem`,
                width: `${size}rem`
            }
        }
    }
}
</script>