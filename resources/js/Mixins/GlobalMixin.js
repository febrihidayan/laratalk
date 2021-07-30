export default {
    data: {
        dark_mode: false,
        laratalk: window.laratalk,
        models: window.laratalk.models,
        trans: window.laratalk.translations,
    },

    methods: {
        setDarkMode(val = null) {
            let bool = val || this.dark_mode

            bool = typeof bool == 'string' ? JSON.parse(bool) : bool

            if (val == null) {
                bool = !bool
            }

            if (bool) {
                document.documentElement.classList.add('dark')
            }
            else {
                document.documentElement.classList.remove('dark')
            }

            localStorage.setItem('dark_mode', bool)
            this.dark_mode = bool
        },

        _setVal(start, center, end = '') {
            return `${start} ${center}` + (end ? ` ${end}` : '')
        },

        getTransMessage(item) {
            const _ = this.laratalk.models.message
            const _t = this.trans.message
            const _type = item.content_type
            const myUser = this.laratalk.profile.id === item.content_by

            // type: 0
            if (_type === _.chat) {
                return this._setVal(
                    !myUser
                        ? item.user_by_name+': ' : '',
                    item.content
                )
            }

            // type: 1
            if (_type === _.create_group) {
                return myUser
                    ? _t.create_group_by
                    : this._setVal(item.user_by_name, _t.create_group)
            }

            // type: 2
            if (_type === _.avatar_group) {
                return this._setVal(item.user_by_name, _t.avatar_group)
            }

            // type: 3
            if (_type === _.rename_group) {
                return this._setVal(
                    item.user_by_name,
                    _t.rename_group_before + ` ${item.content}`,
                    _t.rename_group_after + ` ${item.content}`
                )
            }

            // type: 4
            if (_type === _.description_group) {
                return this._setVal(item.user_by_name, _t.description_group)
            }

            // type: 5
            if (_type === _.info_all_group) {
                return this._setVal(item.user_by_name, _t.info_all_group)
            }

            // type: 6
            if (_type === _.info_admin_group) {
                return this._setVal(item.user_by_name, _t.info_admin_group)
            }

            // type: 7
            if (_type === _.chat_all_group) {
                return this._setVal(item.user_by_name, _t.chat_all_group)
            }

            // type: 8
            if (_type === _.chat_admin_group) {
                return this._setVal(item.user_by_name, _t.chat_admin_group)
            }

            // type: 9
            if (_type === _.add_user_group) {
                if (myUser) {
                    return this._setVal(_t.add_user_group_by, item.user_to_name)
                }
                if (this.laratalk.profile.id === item.content_to) {
                    return this._setVal(item.user_by_name, _t.add_user_group_to)
                }
                
                return this._setVal(
                    item.user_by_name,
                    _t.add_user_group,
                    item.user_to_name
                )
            }

            // type: 10
            if (_type === _.remove_user_group) {
                if (myUser) {
                    return this._setVal(_t.remove_user_group_by, item.user_to_name)
                }
                if (this.laratalk.profile.id === item.content_to) {
                    return this._setVal(item.user_by_name, _t.remove_user_group_to)
                }
                
                return this._setVal(
                    item.user_by_name,
                    _t.remove_user_group,
                    item.user_to_name
                )
            }

            // type: 11
            if (_type === _.add_admin_group && this.laratalk.profile.id === item.content_to) {
                return _t.add_admin_group
            }

            // type: 12
            if (_type === _.remove_admin_group && this.laratalk.profile.id === item.content_to) {
                return _t.remove_admin_group
            }
            
            // type: 13
            if (_type === _.leave_group) {
                return myUser
                    ? _t.leave_group_by
                    : this._setVal(item.user_by_name, _t.leave_group)
            }
        }
    },

    mounted() {
        this.setDarkMode(localStorage.getItem('dark_mode'))
    },

    watch: {
        dark_mode: function(e) {
            this.setDarkMode(e)
        }
    }
}