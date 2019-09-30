<template>
    <div>

        <div class="profile has-text-centered">
            <figure class="image is-128x128" style="display:inline-block">
                <img v-if="user.photo_profil == null" class="is-rounded"  style="display:inline-block" :src="'https://api.adorable.io/avatars/45/' +user.pseudo+'@adorable.png'">
                <img v-if="user.photo_profil != null" class="is-rounded"  style="display:inline-block" :src="user.photo_profil">
            </figure>
        </div>
        <h3 class="title has-text-centered">@{{ user.pseudo }}</h3>
        <div class="bio has-text-centered">
            <p>Bio: {{ user.bio }}</p>
        </div>
           
    </div>
</template>
<script>
    import axios from 'axios'
    export default {
        props: ['userId'],
        data() {
            return {
                user: {
                    photo_profil: ''
                }
            }
        },
        mounted(){
            this.getUser(this.userId)
        },
        watch: {
            userId: function(newVal, oldVal) { // watch it
                this.getUser(newVal)
            }
        },
        methods: {
            getUser(userId){
                if(userId != undefined){
                    let uri = '/user/' + userId
                    axios.get(uri, {
                        headers: {
                                'Authorization': window.localStorage.getItem('token')
                        }
                    })
                    .then((response)=>  {
                        this.user = response.data.user
                        console.log(this.user)
                    })
                }
            }
        }
    }
</script>