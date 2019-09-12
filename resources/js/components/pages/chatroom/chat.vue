<template>
    <div>
       <div class="container">
        <h2 class="title">Chat Room page</h2>
        <p>{{ token }}</p>
       </div>
    </div>
</template>
<script>
    import axios from 'axios'
    const qs = require('querystring')
    export default {
        data(){
            return {
                token: window.localStorage.getItem('token')
            }
        },
        mounted(){
            if(window.localStorage.getItem('token') === null){
                this.$router.push({name: 'login'})
            }
        },
    }
</script>
