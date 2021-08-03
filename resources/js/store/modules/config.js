// state
export const state = {
    languages: null,
    models: null,
    profile: null,
    storage: null,
    translations: null,
}

// getters
export const getters = {
    languages: state => state.languages,
    models: state => state.models,
    profile: state => state.profile,
    storage: state => state.storage,
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
        
        state.languages = config.languages
        state.models = config.models
        state.profile = config.profile
        state.storage = config.storage
        state.translations = config.translations
    }
}