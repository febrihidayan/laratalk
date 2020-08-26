<template>
    <div id="laratalk" :class="[`layout is-flex`, {
        'dark-mode': dark_mode
    }]">
        <aside class="aside is-flex is-pulled-left">
            <div class="aside-header">
                <a class="is-cursor" @click="dark_mode = !dark_mode" style="font-size: 1.75rem;">
                    {{ laratalk.title }}
                    <i :class="[`fa-lightbulb`, {
                        'fas': dark_mode ? false : true,
                        'far': dark_mode ? true : false,
                    }]"></i>
                </a>
                <br><br>
                <input v-model="search" type="search" class="input" placeholder="Cari">
            </div>
            <div class="list-users scroll">
                <article
                    v-for="(item, index) in users"
                    :key="index"
                    @click="isChat = true; fetchMessages(item.id)"
                    :class="[`media is-cursor`, {
                        'is-active': userIndex === index && search == '' ? true : false
                    }]">

                    <figure class="media-left">
                        <p class="image is-48">
                            <img class="is-rounded" src="https://bulma.io/images/placeholders/128x128.png" alt="profile">
                        </p>
                    </figure>
                    <div class="media-content has-color">
                        <strong>{{ item.name }}</strong>
                        <br>
                        <p v-if="item.content">
                            {{
                                (laratalk.user_id != item.to_id ? 'Anda: ' : '')
                                + (item.content.length > 20 
                                ? item.content.substr(0, 20) + '...' 
                                : item.content)
                            }}
                        </p>
                    </div>
                    <div class="media-right has-color">
                        <!-- <i class="media-read fas fa-check"></i> -->
                        <span v-if="item.count" class="badge">{{ item.count }}</span>
                    </div>

                </article>
            </div>
        </aside>
        <main :class="[`main`, {
            'is-active': isChat
        }]">
            <div :class="[`chats`, {
                'is-flex': userIndex != null ? true : false
            }]">
                <header class="chat-header has-shadow">
                    <article class="media">
                        <figure class="media-left">
                            <a class="is-cursor" @click="isChat = !isChat">Back</a>
                            <p class="image is-40">
                                <img class="is-rounded" src="https://bulma.io/images/placeholders/128x128.png" alt="profile">
                            </p>
                        </figure>
                        <div class="media-content">
                            <strong>{{ profile.name }}</strong>
                        </div>
                        <div class="media-right is-cursor">
                            <a @click="isRight = !isRight">Detail</a>
                        </div>
                    </article>
                </header>
                <div class="chat-content scroll" id="chat-content">
                    <div v-for="(item, index) in messages" :key="index">
                        <div v-if="item.from_id == laratalk.user_id" class="message right">
                            <div class="message-content">
                                {{ item.content }}
                            </div>
                        </div>
                        <div v-else class="message">
                            <div class="message-content">
                                {{ item.content }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="chat-footer">
                    <form @submit.prevent="sendMessage">
                        <input v-model="form.content" type="text" class="input rounded" placeholder="Ketik pesan..." required>
                    </form>
                </div>
            </div>
        </main>
        <aside :class="[`aside is-pulled-right`, {
            'is-active': isRight
        }]">
            <header class="has-shadow">
                <div class="media">
                    <div class="media-content">
                        Profile
                    </div>
                    <div class="media-right is-cursor">
                        <a @click="isRight = !isRight">Tutup</a>
                    </div>
                </div>
            </header>
            <div class="content scroll">
                <figure class="image is-128">
                    <img src="https://bulma.io/images/placeholders/128x128.png" alt="">
                </figure>
                <p class="has-color has-text-centered">
                    <strong>{{ profile.name }}</strong>
                </p>
                <hr>
                <div v-for="(name, field) in laratalk.profile" :key="field">
                    <div class="field has-color">
                        <label>{{ name }}</label>
                        <pre v-if="Object.keys(profile).length">{{ profile.field[field] }}</pre>
                    </div>
                </div>
            </div>
        </aside>
    </div>
</template>

<script>
import { debounce } from 'lodash'

export default {
    data() {
        return {
            laratalk: window.Laratalk,
            dark_mode: false,
            isRight: false,
            isChat: false,
            userIndex: null,
            search: '',
            users: [],
            messages: [],
            profile: {},
            form: {
                from_id: window.Laratalk.user_id,
                to_id: '',
                content: ''
            }
        }
    },
    
    methods: {

        fetchUsers()
        {
            let q = this.search || 'all'

            axios.get('user/' + q).then(({ data }) => {
                this.users = data
            })
        },

        fetchMessages(id)
        {
            this.form.to_id = id

            axios.get('message/' + id).then(({ data }) => {
                this.messages = data

                let index = this.users.findIndex((s) => s.id === id)
                this.userIndex = index
                this.profile = this.users[index]
                this.users[index].count = 0
            })
        },

        pushMessage(data, user_id, type = '')
        {
            let index = this.users.findIndex((s) => s.id === user_id)

            if (index != -1 && type == 'push') {
                this.users.splice(index, 1)
            }

            /**
             * if untuk pesan submit
             */
            if (type == '') {
                this.users[index].content = data.content
                this.users[index].to_id = data.to_id

                let user = this.users[index]

                this.users.splice(index, 1)
                this.users.unshift(user)
            }

            /**
             * else untuk pesan dari laravel echo
             */
            else {
                this.users.unshift(data)
            }

            /**
             * Jika dia melihat pesan user
             */
            if (this.form.to_id != '') {
                index = this.users.findIndex((s) => s.id === this.form.to_id)

                this.users[index].count = 0
                this.userIndex = index

                if (this.form.to_id == user_id) {

                    this.messages.push({
                        avatar: data.avatar,
                        content: data.content,
                        created_at: data.created_at,
                        from_id: data.from_id,
                    })

                    axios.put('/message/' + user_id)
    
                }

            }
        },

        sendMessage()
        {
            axios.post('message', this.form).then(({ data }) => {
                this.form.content = ''
                this.search = ''

                this.pushMessage(data, data.to_id)
            })
        },

        fetchEcho()
        {
            Echo.channel('laratalk-user.' + window.Laratalk.user_id)
                .listen('MessageEvent', (e) => {
                    this.pushMessage(e, e.from_id, 'push')
                })
        },

        scrollToEnd()
        {
            let container = this.$el.querySelector("#chat-content")
            container.scrollTop = container.scrollHeight
        }

    },

    mounted() {
        this.fetchUsers()
        this.fetchEcho()
    },

    watch: {
        search: debounce( function() {
            this.fetchUsers()
        }, 500),

        messages: debounce( function() {
            this.scrollToEnd()
        }, 10)
    }
}
</script>