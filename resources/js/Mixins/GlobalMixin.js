import { mapGetters } from 'vuex'

import {
    textPosition
} from './../utils/helpers'

export default {
    computed: mapGetters({
        auth_user: 'config/profile',
        config: 'config/config',
        languages: 'config/languages',
        models: 'config/models',
        trans: 'config/translations',
    }),
    data: {
        dark_mode: false
    },
    methods: {
        setDarkMode(val = null) {
            let bool = val || this.dark_mode

            bool = typeof bool == 'string' ? JSON.parse(bool) : bool

            if (val == null) {
                bool = !bool
            }

            axios.post('user-darkmode', {
                    darkmode: bool
                })

            if (bool) {
                document.documentElement.classList.add('dark')
            }
            else {
                document.documentElement.classList.remove('dark')
            }

            this.dark_mode = bool
        },
        
        getTransMessage(item) {
            const _ = this.models.message
            const trans = this.trans.message
            const type = item.content_type
            const myUser = this.auth_user.id === item.content_by

            // type: 0
            if (type === _.chat) {
                return textPosition(
                    !myUser
                        ? item.user_by_name+': ' : '',
                    item.content
                )
            }
            
            // type: 1
            if (type === _.pull_message) {
                return myUser
                    ? trans.pull_message_by : trans.pull_message
            }

            // type: 2
            if (type === _.create_group) {
                return myUser
                    ? trans.create_group_by
                    : textPosition(item.user_by_name, trans.create_group)
            }

            // type: 3
            if (type === _.avatar_group) {
                return myUser
                    ? trans.avatar_group_by
                    : textPosition(item.user_by_name, trans.avatar_group)
            }

            // type: 4
            if (type === _.rename_group) {
                return textPosition(
                    item.user_by_name,
                    trans.rename_group_before + ` ${item.content}`,
                    trans.rename_group_after + ` ${item.content}`
                )
            }

            // type: 5
            if (type === _.description_group) {
                return textPosition(item.user_by_name, trans.description_group)
            }

            // type: 6
            if (type === _.info_all_group) {
                return textPosition(item.user_by_name, trans.info_all_group)
            }

            // type: 7
            if (type === _.info_admin_group) {
                return textPosition(item.user_by_name, trans.info_admin_group)
            }

            // type: 8
            if (type === _.chat_all_group) {
                return textPosition(item.user_by_name, trans.chat_all_group)
            }

            // type: 9
            if (type === _.chat_admin_group) {
                return textPosition(item.user_by_name, trans.chat_admin_group)
            }

            // type: 10
            if (type === _.add_user_group) {
                if (myUser) {
                    return textPosition(trans.add_user_group_by, item.user_to_name)
                }
                if (this.auth_user.id === item.content_to) {
                    return textPosition(item.user_by_name, trans.add_user_group_to)
                }
                
                return textPosition(
                    item.user_by_name,
                    trans.add_user_group,
                    item.user_to_name
                )
            }

            // type: 11
            if (type === _.remove_user_group) {
                if (myUser) {
                    return textPosition(trans.remove_user_group_by, item.user_to_name)
                }
                if (this.auth_user.id === item.content_to) {
                    return textPosition(item.user_by_name, trans.remove_user_group_to)
                }
                
                return textPosition(
                    item.user_by_name,
                    trans.remove_user_group,
                    item.user_to_name
                )
            }

            // type: 12
            if (type === _.add_admin_group && this.auth_user.id === item.content_to) {
                return trans.add_admin_group
            }

            // type: 13
            if (type === _.remove_admin_group && this.auth_user.id === item.content_to) {
                return trans.remove_admin_group
            }
            
            // type: 14
            if (type === _.leave_group) {
                return myUser
                    ? trans.leave_group_by
                    : textPosition(item.user_by_name, trans.leave_group)
            }
        }
    },

    mounted() {
        this.setDarkMode(this.auth_user.dark_mode)
    },

    watch: {
        dark_mode: function(e) {
            this.setDarkMode(e)
        }
    }
}