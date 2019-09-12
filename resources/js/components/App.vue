<template>
    <div>
        <div class="container">
        <router-link to="/register">Register</router-link>
        <router-link to="/">Login</router-link>
        <router-link to="/chat">Chat</router-link>
        <a href="/logout" @click.prevent="logout">logout</a>
        </div>
        <router-view></router-view>    
    </div>
</template>
<script>
import routes from './routes/router'
export default {
    routes,
    methods:{
        logout(){
            window.localStorage.removeItem('token')
            this.$router.push({name: 'login'})
        }
    }
}
</script>
