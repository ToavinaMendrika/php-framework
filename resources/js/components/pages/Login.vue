<template>
    <div>
       
            <div class="section">
                <div class="container">
                    <div class="title">Login</div>
                    <div class="subtitle">With Your Email</div>
                    <article class="message is-danger" v-if="message.display">
                        <div class="message-header">
                            <p>Erreur</p>
                            <button class="delete" aria-label="delete"></button>
                        </div>
                        <div class="message-body">
                            <strong>{{ message.text }}</strong>
                        </div>
                    </article>
                    <form id="register-form" @submit.prevent="login">
                        <div class="field">
                            
                            <label class="label" for="email">Email</label>
                            <div class="control has-icons-left">
                                <input class="input" type="email" placeholder="Email" name="email" v-model="user.email"/><span
                                    class="icon is-left">
                                    <i class="fa-envelope"></i>
                                    </span>
                            </div>
                      
                            
                            <div class="column">
                                <label class="label" for="password">Password</label>
                                <div class="control has-icons-left">
                                    <input class="input" type="password" placeholder="Password" v-model="user.pass"
                                        name="password" /><span class="icon is-left"><i class="fa-key"></i></span>
                                </div>
                            </div>
                                                    
                            <div class="field is-grouped">
                                <div class="control">
                                    <button class="button is-medium is-primary" type="submit">Login</button>
                                </div>
                                <div class="control">
                                    <button class="button  is-medium" type="submit">Register</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
    </div>
</template>
<script>
    import axios from 'axios'
    const qs = require('querystring')
    export default {
        data(){
            return {
                user: {
                    pass: '',
                    email: '',
                },
                message: {
                    display: false,
                    type: '',
                    text: ''
                }
            }
        },
        mounted(){
            if(window.localStorage.getItem('token') !== null){
                this.$router.push('chat')
            }
        },
        methods:{
            login(){
                this.message.display = false
                console.log('register: ', qs.stringify(this.user)); 
                axios.post('/user/login', qs.stringify(this.user),{
                     headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    }
                })
                .then((response)=>  {
                    if(response.data.status == 'error'){
                        this.message.display = true,
                        this.message.type = response.data.status,
                        this.message.text = response.data.token

                    }
                    else{
                        window.localStorage.setItem('token', response.data.token)
                        this.$router.push('chat')
                    }
                   
                })
                .catch(function (error) {
                    console.log(error);
                });
            }
        }
    }
</script>
