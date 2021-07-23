export default {
    methods: {
        getTypeId(type, id)
        {
            return `${type}-${id}`
        },

        firstName(string)
        {
            return string.split(' ')[0]
        },

        getTitleLeftDetail(type)
        {
            if (type === 'profile') {
                return this.trans.profile
            }
            if (type === 'chat') {
                return this.trans.new_chat
            }
            if (type === 'group') {
                return this.trans.select_group_participants
            }
            if (type === 'settings') {
                return this.trans.settings
            }
        }
    }
}