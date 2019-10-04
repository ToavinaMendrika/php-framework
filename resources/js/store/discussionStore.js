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
        }, 
        getDiscussionById(state){
            return discussionId => state.discussions.discussions.filter(discussion =>{
                return discussion.id === discussionId
            })
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
        },

        updateDiscussion(state, discussionId){
            state.discussions.discussions.forEach(discussion => {
                if(discussion.id === 'new'){
                    discussion.id = discussionId
                    discussion.last_message.date_envoi = moment().endOf('hour').fromNow(); 
                }
            })
        },

        moveTofirstPosition(state, discussionId){
            var first = discussionId
            state.discussions.discussions.sort(function(x,y){ return x.id == first ? -1 : y.id == first ? 1 : 0; });
        },

        clean(state){
            state.discussions = []
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
                    discussion.users.forEach(user => {
                        if(user.id != response.data.current_user_id){
                            discussion.name = user.pseudo
                        }
                    });
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
        }, 

        updateDiscussion(context, discussionId){
            context.commit('updateDiscussion', discussionId)
        },

        moveTofirstPosition(context, discussionId){
            context.commit('moveTofirstPosition', discussionId)
        },
        clean(context){
            context.commit('clean')
        }

    }
})

export default store