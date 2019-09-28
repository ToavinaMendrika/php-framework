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
                <span v-for="user in current_discussion.users">
                    {{user}}
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
              :style="{ position: 'absolute', bottom: '50px', right: '20px' , display: displayEmojis}"
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
        <div class="bordered about">
          <div class="about-header has-text-centered">
            <img class="avatar" src="https://api.adorable.io/avatars/45/toavina@adorable.png" alt="">
          </div>
          <p class="has-text-centered">   
         
          Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quaerat fugiat dolorum quidem possimus, quas hic veritatis 
          </p>
          <p class="has-text-centered">
            <a href="#" class="button is-info">Voir le profil</a>
          </p>
          <hr>
          <div class="invite">
            <p>Inviter: </p>
            <div class="control">
              <input type="text" class="input" placeholder="Inviter ...">
            </div>
            <div class="result">
                <div class="card">
                  <div class="card-content">
                    <div class="columns">
                        <div class="column">
                          <img src="https://api.adorable.io/avatars/45/toavina@adorable.png" alt="">
                        </div>
                        <div class="column">
                          <p>John Doe</p>
                        </div>
                        <div class="column">
                          <button class="button is-info">Ajouter</button>
                        </div>
                    </div>
                  </div>
                </div>
                <div class="card">
                  <div class="card-content">
                    <div class="columns">
                        <div class="column">
                          <img src="https://api.adorable.io/avatars/45/toavina@adorable.png" alt="">
                        </div>
                        <div class="column">
                          <p>John Doe</p>
                        </div>
                        <div class="column">
                          <button class="button is-info">Ajouter</button>
                        </div>
                    </div>
                  </div>
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
    import { Picker } from 'emoji-mart-vue'
    const qs = require('querystring')
    export default {
        store: store,
        components: {
          Picker
        },
        data(){
            return {
                displayEmojis: 'none',
                newMessage: {
                    message: '',
                    type: 'text',
                    user: {
                        id: null
                    }
                },
                current_user: null,
                messages: [],
                current_discussion: {
                    users: [],
                    avatars: []
                }
            }
        },
        mounted(){
            if(window.localStorage.getItem('token') === null){
                this.$router.push({name: 'login'})
            }
            else{
                this.getMessages(this.$route.params.id)
            }
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
                    response.data.messages.forEach((message) =>{
                        let avatar = message.user.photo_profil
                        let pseudo = message.user.pseudo
                        let defaultAvatar = 'https://api.adorable.io/avatars/45/'+pseudo+'@adorable.png'
                        if(!this.current_discussion.users.includes(pseudo)){
                            this.current_discussion.users.push(pseudo)
                        }
                        /*
                        if(!this.current_discussion.avatars.includes(avatar) || !this.current_discussion.avatars.includes(defaultAvatar)  && avatar !== null){
                            this.current_discussion.avatars.push(avatar)
                        }
                        else{          
                            this.current_discussion.avatars.push(defaultAvatar)
                        }*/
                    })
                })
            },

            sendMessage(){
                let uri = '/chat/discussion/' + this.$route.params.id
                let newMessage = {
                    type: this.newMessage.type,
                    msg_text: this.newMessage.message,
                    user: {
                        id: this.newMessage.user.id
                    }
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
              if(this.displayEmojis == 'block'){
                this.displayEmojis = 'none'
              }
              else{
                this.displayEmojis = 'block'
              }
            }

        },
        watch:{
            $route (to, from){
                this.getMessages(this.$route.params.id)
            }
        } 
    }
</script>
