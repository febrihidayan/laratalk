// state
export const state = {
    config: null,
    languages: null,
    models: null,
    profile: null,
    translations: null,
}

// getters
export const getters = {
    config: state => state.config,
    languages: state => state.languages,
    models: state => state.models,
    profile: state => state.profile,
    translations: state => state.translations,
}

// mutations
export const mutations = {

}

// actions
export const actions = {
    fetchConfig({ state })
    {
        const config = window.laratalk
        
        state.config = config.config
        state.languages = config.languages
        state.models = config.models
        state.profile = config.profile
        state.translations = config.translations
    },

    fetchTranslation({ state }, locale)
    {
        axios
            .post('user-language', {
                locale
            })
            .then(({ data }) => {
                state.translations = data
                window.laratalk.translations = data
            })
    }
}