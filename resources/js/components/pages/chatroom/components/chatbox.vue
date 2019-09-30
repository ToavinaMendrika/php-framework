<template>
    <div class="column">
        <div class="columns is-gapless">
            <div class="column">
          <div class="bordered messages">
            <div class="header">
              <span v-for="avatar in current_discussion.avatars">
                <img class="avatar" :src="avatar" alt="">
              </span>
              <span class="discussion-title">
                <span v-for="user in current_discussion.users" v-if="!user.is_current_user">
                    {{user.pseudo}}
                </span>
              </span>
            </div>
            <div class="message-box" id="message-box">

                <span v-for="message in messages">
                <div class="message-container">
                    <div class="message-content me" v-if="message.user.id == current_user">
                        <p>{{ message.msg_text }}</p>
                    </div> 
                    <div class="message-content other" v-if="message.user.id != current_user">
                        <img class="avatar" :src="'https://api.adorable.io/avatars/45/'+message.user.pseudo+'@adorable.png'" alt="">
                        <p>{{ message.msg_text }}</p>
                    </div> 
                </div>
                </span>
     
            </div>
            <div class="message-control">
            <picker 
              :title="'Simple emoji'" 
              :exclude="['symbols','objects','places','flags', 'activity']" 
              :style="{ position: 'absolute', bottom: '50px', right: '20px'}"
              v-show="displayEmojis"
              @select="addEmoji"
             /> 
              <form action="" method="post" @submit.prevent="sendMessage">
                <input style="width:97%" class="input" type="text" placeholder="Message" data-emoji-picker="true" v-model="newMessage.message">
                <i class="fas fa-plus" @click="showEmojis"></i>
              </form>
            </div>
          </div>
      </div>
        <div class="column is-one-quarter">
          <div class="bordered about has-text-centered">
            <user-profil :user-id="other_user" v-if="other_user != null"/>
            <router-link class="button is-info" :to="{name: 'chat_user', params: {id: other_user}}" v-if="other_user != null">Voir profil</router-link>
          </div>
        </div>
        </div>
    </div>
</template>
<script>
    import axios from 'axios'
    import store from '../../../../store/discussionStore'
    import { Picker } from 'emoji-mart-vue'
    import UserProfil from './userProfile'
    const qs = require('querystring')
    export default {
        store: store,
        components: {
          Picker,
          UserProfil
        },
        data(){
            return {
                displayEmojis: false,
                newMessage: {
                    message: '',
                    type: 'text',
                    user:{
                      id: null
                    }
                },
                current_user: null,
                other_user: null,
                messages: [],
                current_discussion: {
                    users: [],
                    avatars: []
                }
            }
        },
        created(){
          if(window.localStorage.getItem('token') === null){
                this.$router.push({name: 'login'})
            }
            else{
              if(this.$route.params.id == 'new'){
                this.current_discussion.users.push({
                  pseudo: 'Nouvelle discussion'
                })
              }else{
                this.getMessages(this.$route.params.id)
              }
            }
        },
        mounted(){
            
        },
        methods: {
            getMessages(discussionId){
                let uri = '/chat/discussion/' + discussionId
                axios.get(uri, {
                    headers: {
                            'Authorization': window.localStorage.getItem('token')
                    }
                })
                .then((response)=>  {
                        
                    this.messages = response.data.messages
                    this.current_user = response.data.current_user_id
                    this.newMessage.user.id = response.data.current_user_id
                    this.current_discussion.users = []
                    this.current_discussion.avatars = []
                    window.setTimeout( ()=>{
                        document.getElementById('message-box').scrollTop = document.getElementById('message-box').scrollHeight
                        this.seenMessage(discussionId)
                    },1000)
                    let current_user = []
                    response.data.messages.forEach((message) =>{
                        if(!current_user.includes(message.user.id)){
                          current_user.push(message.user.id)
                          let user = {
                            is_current_user: message.user.id == this.current_user,
                            ...message.user
                          }
                          if(!user.is_current_user){
                            this.other_user = user.id
                          }
                          this.current_discussion.users.push(user)
                        }
                       
                        
                        /*
                        if(!this.current_discussion.avatars.includes(avatar) || !this.current_discussion.avatars.includes(defaultAvatar)  && avatar !== null){
                            this.current_discussion.avatars.push(avatar)
                        }
                        else{          
                            this.current_discussion.avatars.push(defaultAvatar)
                        }*/
                    })
                    console.log(this.current_discussion)
                    console.log(this.other_user)
                })
            },

            sendMessage(){
                
                let uri = this.$route.params.id != 'new' ? '/chat/discussion/' + this.$route.params.id : '/chat/discussion_profil'
                let newMessage = {
                    type: this.newMessage.type,
                    msg_text: this.newMessage.message,
                    user: {
                      id: this.newMessage.user.id 
                    } 

                }
                if( this.$route.params.id == 'new' ){
                  this.newMessage.user_id =  store.getters.discussions.discussions[0].users[0].id
                }
                this.messages.push(newMessage)
                axios.post(uri, qs.stringify(this.newMessage),{
                     headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'Authorization': window.localStorage.getItem('token')
                    }
                },qs.stringify(this.newMessage))
                .then((response)=>  {
                        this.newMessage.message = ''
                        document.getElementById('message-box').scrollTop = document.getElementById('message-box').scrollHeight
                        
                })
            },

            seenMessage(discussionId){
              store.dispatch('seenMessage', discussionId)
            },

            addEmoji(emoji){
              this.newMessage.message += emoji.native
            },

            showEmojis(){
              this.displayEmojis = !this.displayEmojis
            }

        },
        watch:{
            $route (to, from){
                this.getMessages(this.$route.params.id)
            }
        } 
    }
</script>
