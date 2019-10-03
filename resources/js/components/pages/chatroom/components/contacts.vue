<template>
    <div class="column">
        <div class="bordered">
            <div class="search-wrapper">
                <h3 class="title">Mes contacts</h3>
                <hr>

                <div class="columns" style="flex-wrap:wrap">
                    <div class="column is-4" v-for="contact in contacts">
                        <div class="box">
                            <article class="media">
                                <div class="media-left">
                                    <figure class="image is-64x64">
                                        <img v-if="contact.photo_profil == null" class="is-rounded"  style="display:inline-block" :src="'https://api.adorable.io/avatars/45/' +contact.pseudo+'@adorable.png'">
                                        <img v-if="contact.photo_profil != null" class="is-rounded"  style="display:inline-block" :src="contact.photo_profil">
                                    </figure>
                                </div>
                                <div class="media-content">
                                    <div class="content">
                                        <p>
                                            <strong>{{contact.pseudo}}</strong> <small>@{{contact.pseudo}}</small>
                                        </p>
                                    </div>
                                    <nav class="level is-mobile">
                                        <div class="level-left">
                                            <button @click.prevent="sendMessageTo(contact)" class="button is-small is-info">
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
        </div>
    </div>
    </div>
</template>
<script>
    import axios from 'axios'
    import store from '../../../../store/discussionStore'
     const qs = require('querystring')
    export default {
        store: store,
        data() {
            return {
                contacts: []
            }
        },
        mounted(){
            this.getAllContacts()
        },
        methods:{
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
            getAllContacts(){
                let uri = '/user_profil/list_contact/self'
                 axios.get(uri,{
                    headers: {
                        'Authorization': window.localStorage.getItem('token')
                    }
                })
                .then((response)=>  {
                    if(response.data.status == 'success'){
                        this.contacts = response.data.list_contact
                    }
                })
            }
        }
    }
</script>