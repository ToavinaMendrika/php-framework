<template>
    <div class="column">
        <div class="bordered">
            <div class="search-wrapper">
                <h3 class="title has-text-centered">Rechercher</h3>
                <div class="field">
                    <p :class="'control has-icons-left ' + loading">
                        <input class="input" type="email" placeholder="Nom d'utilisateur" v-model="q" @keyup="searchUser">
                        <span class="icon is-small is-left">
                            <i class="fas fa-search"></i>
                        </span>   
                    </p>
                </div>
                <div class="box">
                    <article class="media" v-for="user in search.results.users">
                        <div class="media-left">
                            <figure class="image is-64x64">
                                <img :src="'https://api.adorable.io/avatars/45/'+ user.pseudo +'@adorable.png'" alt="Image">
                            </figure>
                        </div>
                        <div class="media-content">
                            <div class="content">
                                <p>
                                    <strong>{{ user.pseudo }}</strong> <small>@{{ user.pseudo}}</small> <small>31m</small>
                                    <br>
                                    {{ user.bio }}
                                </p>
                            </div>
                            <nav class="level is-mobile">
                                <div class="level-left">
                                    <a class="level-item" aria-label="reply">
                                        <span class="icon is-small">
                                            <i class="fas fa-reply" aria-hidden="true"></i>
                                        </span>
                                    </a>
                                    <a class="level-item" aria-label="retweet">
                                        <span class="icon is-small">
                                            <i class="fas fa-retweet" aria-hidden="true"></i>
                                        </span>
                                    </a>
                                    <a class="level-item" aria-label="like">
                                        <span class="icon is-small">
                                            <i class="fas fa-heart" aria-hidden="true"></i>
                                        </span>
                                    </a>
                                </div>
                            </nav>
                        </div>
                    </article>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import axios from 'axios'
    import _ from 'lodash'
    const qs = require('querystring')
    export default {
        data(){
            return {
                q: '',
                loading: '',
                search: {
                    results: {
                        users: []
                    }
                }
            }    
        },
        methods: {
           searchUser: _.debounce(function(){          
                if(this.q !== ''){
                    this.loading = 'is-loading'
                    let uri = '/user/search'
                    
                    axios.post(uri, qs.stringify({'search': this.q, 'scope': 'global'}),{
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                            'Authorization': window.localStorage.getItem('token') 
                        }
                    })
                    .then((response)=>  {
                        if(response.status == 200){
                            this.search.results = response.data
                            this.loading = ''
                        }
                    })       
                }
                else{
                    this.search.results.users = []
                }
               
           }, 500)
        }
        

    }
</script>
