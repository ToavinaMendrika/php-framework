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
                <a class="navbar-item">
                Home
                </a>
        
                <a class="navbar-item">
                Documentation
                </a>
        
                
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
    export default {
        data(){
            return {
                currentUser : {}
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
        },
        methods:{
            logout(){
                window.localStorage.removeItem('token')
                this.$router.push({name: 'login'})
            }
        }
    }
</script>
