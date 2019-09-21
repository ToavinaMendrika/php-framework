import Vuex from 'vuex'
import axios from 'axios'
import Vue from 'vue';
import moment from 'moment'

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
            
        }
    }
})

export default store