<template>
    <div class="column">
        <div class="bordered">
            <div class="search-wrapper">
                <h3 class="title has-text-centered">Invitation contact</h3>
                <hr>
                 <div class="box">
                    <article class="media" v-for="user in request.users">
                        <div class="media-left">
                            <figure class="image is-64x64">
                                <img :src="'https://api.adorable.io/avatars/45/'+ user.pseudo +'@adorable.png'" alt="Image">
                            </figure>
                        </div>
                        <div class="media-content">
                            <div class="content">
                                <p>
                                    <strong>{{ user.pseudo }}</strong> <router-link :to="{name: 'chat_user', params: {id: user.id} }"><small>@{{ user.pseudo}}</small></router-link> <small>{{ formatedDate(user.date_envoi) }}</small>
                                </p>
                            </div>
                            <nav class="level is-mobile">
                                <div class="level-right">
                                    <button @click.prevent="responseInvitation(user, 'accept')" class="button is-small is-info" v-show="!user.accepted">Accepter</button>    
                                    <button @click.prevent="responseInvitation(user, 'refuse')" class="button is-small is-info" v-show="!user.accepted">Rejeter</button>    
                                    <button @click.prevent="sendMessageTo(user)" class="button is-small is-info" v-show="user.accepted">
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
import moment from 'moment'
import store from '../../../../store/discussionStore'
const qs = require('querystring')
export default{
    store: store,
    data(){
        return {
            request: {
                users: []
            }
        }
    },
    mounted(){
        this.getAllRequest()
    },
    methods:{
        getAllRequest(){
           
            let uri = '/user/request/all'
            axios.post(uri, {},{
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'Authorization': window.localStorage.getItem('token') 
                }
            })
            .then((response)=>  {
                if(response.status == 200){
                    let users = []
                    response.data.users.forEach(function(user){
                        users.push({
                            accepted: false,
                            ...user
                        })
                    })
                    this.request.users = users
                }
            }) 
            
        },
        formatedDate(date){
            return moment(date).startOf('hour').fromNow()
        },
        responseInvitation(user, responseText){
            let uri = '/user/request/response'
            axios.post(uri,  qs.stringify({'send_id': user.id, 'response': responseText}),{
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'Authorization': window.localStorage.getItem('token') 
                }
            })
            .then((response)=>  {
                if(response.data.status == 'success'){
                    if(responseText === 'accept'){
                        let index = this.request.users.indexOf(user)
                        this.request.users[index].accepted = true
               
                    }
                    else{
                        let index = this.request.users.indexOf(user)
                        this.request.users.splice(index, 1);
                    }
                }else{
                    console.log('error')
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
    }
}
</script>