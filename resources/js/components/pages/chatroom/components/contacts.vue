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
                                        <img src="https://bulma.io/images/placeholders/128x128.png" alt="Image">
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
    export default {
        store: store,
        data() {
            return {
                contacts: [
                    {
                        id: 25,
                        pseudo: 'test',
                    }
                ]
            }
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
            }
        }
    }
</script>