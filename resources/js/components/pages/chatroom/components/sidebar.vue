<template>
    <div class="column is-one-fifth ">
        <div class="bordered discussion">
          <!-- begin card discussion -->

        <span v-for="discussion in discussions">
            <router-link :to="{ name: 'chat_box', params: { id: discussion.id }}">
            <div class="discussion-card">
              <div class="avatar">
                <img :src="'https://api.adorable.io/avatars/45/'+ discussion.users[1].pseudo +'@adorable.png'" alt="">
                <span class="not-read" v-if="discussion.notseen !== '0'">{{ discussion.notseen }}</span>
              </div>
              <div class="discussion-body">
                <p>{{ discussion.users[1].pseudo }}</p>
                <small class="date">{{ discussion.last_message.date_envoi }}</small>               
              </div>
              <div class="status">
                <span class="connected"></span>
              </div>
            </div>
            </router-link>
        </span>
           <!-- end card discussion -->
          
        </div>
      </div>
</template>
<script>
    import axios from 'axios'
    import moment from 'moment'
    const qs = require('querystring')
    export default {       
        data(){
            return {
                discussions: []
            }
        },
        mounted(){
            axios.get('/chat/discussion',{
                     headers: {
                        'Authorization': window.localStorage.getItem('token')
                    }
                })
                .then((response)=>  {
                    this.discussions = response.data.discussions
                    console.log(response);
                    window.setTimeout(function(){
                        document.querySelectorAll('.date').forEach(function(el){
                        let date = el.innerText
                        el.innerText = moment(date).fromNow()
                    },500)
                    })
                })
                .catch(function (error) {
                    console.log(error);
                });

            
        },
    }
</script>
