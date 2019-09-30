import Vuex from 'vuex'
import axios from 'axios'
import Vue from 'vue';
import moment from 'moment'
const qs = require('querystring')

Vue.use(Vuex);
const store = new Vuex.Store({
    state: {
        discussions : []
    },
    getters: {
        discussions(state){
            return state.discussions
        }
    },
    mutations: {
        addDiscussions(state, discussions){
            state.discussions = discussions
        },
        addDiscussion(state, discussion){
            state.discussions.discussions.unshift(discussion)
        },

        seenMessage(state, discussionId){
            state.discussions.discussions.forEach(discussion => {
                if(discussion.id === discussionId){
                    discussion.notseen = 0
                }
            })
        }
    },
    actions: {
        addDiscussions(context){
            axios.get('/chat/discussion',{
                headers: {
                   'Authorization': window.localStorage.getItem('token')
               }
           })
           .then((response)=>  {
                let currentDiscussions = {}
                let discussions = []           
                response.data.discussions.forEach(discussion => {
                    discussion.last_message.date_envoi = moment(discussion.last_message.date_envoi).startOf('hour').fromNow()
                    discussions.push(discussion)
                });
                currentDiscussions.current_user_id = response.data.current_user_id
                currentDiscussions.discussions = discussions
                context.commit('addDiscussions', currentDiscussions)
                
              
           })
           .catch(function (error) {
               console.log(error);
           });
            
        },

        seenMessage(context, discussionId){
            let uri = '/chat/message/seen'
            this.state.discussions.discussions.forEach(discussion => {
                if(discussion.id === discussionId && discussion.notseen != 0){
                    axios.post(uri, qs.stringify({'discussion_id': discussionId}),{
                        headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'Authorization': window.localStorage.getItem('token')
                    }
                    })
                    .then((response)=>  {
                            if(response.status == 200){
                                context.commit('seenMessage', discussionId)
                            }
                    })
                }
            })
            
        },

        addDiscussion(context, discussion){
            context.commit('addDiscussion', discussion)
        }

    }
})

export default store