<template>
    <div>
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
