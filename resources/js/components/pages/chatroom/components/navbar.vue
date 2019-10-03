<template>
    <div>
       <nav class="navbar is-primary is-fixed-top" role="navigation" aria-label="main navigation">
            <div class="navbar-brand">
            <a class="navbar-item" href="#">
                <img src="/img/Logo.png">
            </a>
        
            <a role="button" class="navbar-burger burger" aria-label="menu" aria-expanded="false" data-target="navbarBasicExample">
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
            </a>
            </div>
        
            <div id="navbarBasicExample" class="navbar-menu">
            <div class="navbar-start">
                <router-link :to="{name: 'chat_home'}" class="navbar-item">Home</router-link>
                <router-link :to="{name: 'chat_contacts'}" class="navbar-item">Contacts</router-link>   
                <router-link :to="{name: 'chat_invitation'}" class="navbar-item">Invitation
                    <span :class="'not-read nbr-' + nbrRequest" v-show="nbrRequest > 0">{{ nbrRequest }}</span>
                </router-link>   
            </div>
        
            <div class="navbar-end">
                <div class="navbar-item has-dropdown is-hoverable">
                <a class="navbar-link">
                    <img class="avatar" :src="'https://api.adorable.io/avatars/45/'+currentUser.pseudo+'@adorable.png'" alt=""> {{currentUser.pseudo}}
                </a>
        
                <div class="navbar-dropdown is-right">
                    <a class="navbar-item">
                    Parametre
                    </a>
                    <a class="navbar-item">
                    contacts
                    </a>
                    <hr class="navbar-divider">
                    <a class="navbar-item" @click.prevent="logout">
                    Se déconnecter
                    </a>
                </div>
                </div>
            </div>
            </div>
        </nav>
    </div>
</template>
<script>
    import axios from 'axios'
    import store from '../../../../store/discussionStore'
    export default {
        store: store,
        data(){
            return {
                currentUser : {},
                nbrRequest: 0
            }
        },
        mounted(){
            axios.get('/user/self',{
                headers: {
                   'Authorization': window.localStorage.getItem('token')
               }
           })
           .then((response)=>  {
              this.currentUser = response.data.user
              console.log(this.currentUser)
           })
           .catch(function (error) {
               console.log(error);
           });
           this.getAllRequest()
        },
        methods:{
            logout(){
                store.dispatch('clean')
                window.localStorage.removeItem('token')
                this.$router.push({name: 'login'})
            },
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
                        this.nbrRequest = response.data.nb_not_seen
                    }
                }) 
                
            },
            
            seenAllRequest(){
                let uri = '/user/request/seen'
                axios.post(uri, {},{
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'Authorization': window.localStorage.getItem('token') 
                    }
                })
                .then((response)=>  {
                    if(response.status == 200){
                        this.nbrRequest = 0
                    }
                }) 
            }
        },
        watch:{
            $route (to, from){
                if(this.$route.name == 'chat_invitation'){
                    if(this.nbrRequest > 0){
                        this.seenAllRequest()
                    }
                }
            }
        }  
    }
</script>
