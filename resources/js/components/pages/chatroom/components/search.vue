<template>
    <div class="column">
        <div class="bordered">
            <div class="search-wrapper">
                <h3 class="title has-text-centered">Rechercher</h3>
                <div class="field">
                    <p :class="'control has-icons-left ' + loading">
                        <input class="input" type="email" placeholder="Nom d'utilisateur" v-model="q" @keyup="searchUser">
                        <span class="icon is-small is-left">
                            <i class="fas fa-search"></i>
                        </span>   
                    </p>
                </div>
                <div class="box">
                    <article class="media" v-for="user in search.results.users">
                        <div class="media-left">
                            <figure class="image is-64x64">
                                <img :src="'https://api.adorable.io/avatars/45/'+ user.pseudo +'@adorable.png'" alt="Image">
                            </figure>
                        </div>
                        <div class="media-content">
                            <div class="content">
                                <p>
                                    <strong>{{ user.pseudo }}</strong> <router-link :to="{name: 'chat_user', params: {id: user.id} }"><small>@{{ user.pseudo}}</small></router-link> <small>31m</small>
                                    <br>
                                    {{ user.bio }}
                                </p>
                            </div>
                            <nav class="level is-mobile">
                                <div class="level-right">
                                    <button @click.prevent="addToContact(user, $event)" class="button is-small is-info" v-if="user.is_friend != 'true' && !user.is_current_user">Ajouter</button>
                                    <button @click.prevent="sendMessageTo(user)" class="button is-small is-info" v-if="user.is_friend == 'true' && !user.is_current_user">
                                        <span class="icon is-small">
                                            <i class="fas fa-paper-plane"></i>
                                        </span>
                                        <span>Message</span>
                                    </button>
                                </div>
                            </nav>
                        </div>
                    </article>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import axios from 'axios'
    import _ from 'lodash'
    import store from '../../../../store/discussionStore'
    const qs = require('querystring')
    export default {
        store: store,
        data(){
            return {
                q: '',
                loading: '',
                search: {
                    results: {
                        users: []
                    }
                }
            }    
        },
        methods: {
            addToContact(user, event){
                let uri = '/user/request/add'
                axios.post(uri, qs.stringify({'user_id': user.id}),{
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'Authorization': window.localStorage.getItem('token') 
                    }
                })
                .then((response)=>  {
                    if(response.status == 200){
                        event.target.innerText = 'invitation'
                    }
                }) 
            },
            sendMessageTo(contact){
                let discussions = store.getters.discussions
                let foundDiscussion = null
                discussions.discussions.forEach(discussion => {
                    discussion.users.forEach(user => {
                        if(user.id == contact.id){
                            foundDiscussion = discussion
                        }
                    })
                })
                if(foundDiscussion != null){
                    this.$router.push({
                        name: 'chat_box',
                        params: {id: foundDiscussion.id}
                    })
                }else{
                   let discussion = {
                       id: 'new',
                       last_message: {
                           date_envoi: 'En train d\'ecrire ...'
                       },
                       notseen: 0,
                       name: contact.pseudo,
                       type: 'individual',
                       users: [
                           contact
                       ]
                   }
                   store.dispatch('addDiscussion', discussion)
                   this.$router.push({
                        name: 'chat_box',
                        params: {id: discussion.id}
                    })
                }
            },
            searchUser: _.debounce(function(){          
                    if(this.q !== ''){
                        this.loading = 'is-loading'
                        let uri = '/user/search'
                        
                        axios.post(uri, qs.stringify({'search': this.q, 'scope': 'global'}),{
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                                'Authorization': window.localStorage.getItem('token') 
                            }
                        })
                        .then((response)=>  {
                            if(response.status == 200){
                                this.search.results = response.data
                                this.loading = ''
                            }
                        })       
                    }
                    else{
                        this.search.results.users = []
                    }
                
                }, 500)
            }

    }
</script>
