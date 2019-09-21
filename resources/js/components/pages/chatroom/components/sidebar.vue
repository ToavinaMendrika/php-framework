<template>
    <div class="column is-one-fifth ">
        <div class="bordered discussion">
          <!-- begin card discussion -->

        <span v-for="discussion in discussions.discussions">
            <router-link :to="{ name: 'chat_box', params: { id: discussion.id }}">
            <div class="discussion-card">
              <div class="avatar">
                <span v-for="user in discussion.users">
                  <img v-if="user.id != discussions.current_user_id" :src="'https://api.adorable.io/avatars/45/'+ user.pseudo +'@adorable.png'" alt="">
                </span> 
                <span class="not-read" v-if="discussion.notseen !== '0'">{{ discussion.notseen }}</span>
              </div>
              <div class="discussion-body">
                <span v-for="user in discussion.users">
                  <p v-if="user.id != discussions.current_user_id">{{ user.pseudo }}</p>
                </span> 
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
    import store from '../../../../store/discussionStore'
    const qs = require('querystring')
    export default {
        store: store, 
        data(){
            return {
                //discussions: []
            }
        },
        computed: {
            discussions(){
              return store.getters.discussions
            }
        },
        mounted(){
            store.dispatch('addDiscussions')
        }
    }
</script>
