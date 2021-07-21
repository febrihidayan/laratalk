export default {
    methods: {
        getTypeId(type, id)
        {
            return `${type}-${id}`
        },

        firstName(string)
        {
            return string.split(' ')[0]
        }
    }
}