<template>
    <div>
        <navbar></navbar>
        <main class="main">
            <div class="columns is-gapless">
                 <sidebar></sidebar>
                 <router-view></router-view>
            </div>
        </main>
    </div>
</template>
<script>
    import axios from 'axios'
    import navbar from './components/navbar'
    import sidebar from './components/sidebar'
    const qs = require('querystring')
    export default {
        components: {
            navbar,
            sidebar
        },
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
