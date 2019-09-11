<template>
    <div>
        <form action="post">
            <div class="section">
                <div class="container">
                    <div class="title">Sign Up</div>
                    <div class="subtitle">With Your Email</div>
                    <article class="message is-success" v-if="message.display">
                        <div class="message-header">
                            <p>Success</p>
                            <button class="delete" aria-label="delete"></button>
                        </div>
                        <div class="message-body">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. <strong>Pellentesque risus mi</strong>, tempus quis placerat ut, porta nec nulla. Vestibulum rhoncus ac ex sit amet fringilla. Nullam gravida purus diam, et dictum <a>felis venenatis</a> efficitur. Aenean ac <em>eleifend lacus</em>, in mollis lectus. Donec sodales, arcu et sollicitudin porttitor, tortor urna tempor ligula, id porttitor mi magna a neque. Donec dui urna, vehicula et sem eget, facilisis sodales sem.
                        </div>
                    </article>
                    <form id="register-form" @submit.prevent="register">
                        <div class="field">
                            <label class="label" for="username">Username</label>
                            <div class="control has-icons-left">
                                <input class="input" type="text" placeholder="Username" name="username" v-model="user.username" /><span
                                    class="icon is-left"><i class="fa-user"></i></span>
                            </div>
                            <label class="label" for="email">Email</label>
                            <div class="control has-icons-left">
                                <input class="input" type="email" placeholder="Email" name="email" v-model="user.email"/><span
                                    class="icon is-left">
                                    <i class="fa-envelope"></i>
                                    </span>
                            </div>
                            <div class="columns row-one">
                                <div class="column">
                                    <label class="label" for="firstName">First Name</label>
                                    <div class="control">
                                        <input class="input" type="text" placeholder="First Name" name="firstName" />
                                    </div>
                                </div>
                                <div class="column">
                                    <label class="label" for="lastName">Last Name</label>
                                    <div class="control">
                                        <input class="input" type="text" placeholder="Last Name" name="lastName" />
                                    </div>
                                </div>
                            </div>
                            <div class="columns">
                                <div class="column">
                                    <label class="label" for="password">Password</label>
                                    <div class="control has-icons-left">
                                        <input class="input" type="password" placeholder="Password" v-model="user.pass"
                                            name="password" /><span class="icon is-left"><i class="fa-key"></i></span>
                                    </div>
                                </div>
                                <div class="column">
                                    <label class="label" for="retypePassword">Re-Type Password</label>
                                    <div class="control has-icons-left">
                                        <input class="input" type="password" placeholder="Confirm Password"
                                            name="retypePassword" /><span class="icon is-left"><i
                                                class="fa">lock</i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="field is-grouped">
                                <div class="control">
                                    <button class="button is-medium">Login</button>
                                </div>
                                <div class="control">
                                    <button class="button is-primary is-medium" type="submit">Register</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </form>
    </div>
</template>
<script>
    import axios from 'axios'
    const qs = require('querystring')
    export default {
        data(){
            return {
                user: {
                    username: '',
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
        methods:{
            register(){
                console.log('register: ', qs.stringify(this.user)); 
                axios.post('/user/register', qs.stringify(this.user),{
                     headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    }
                })
                .then((response)=>  {
                    if(response.data.status == 'success'){
                        this.message.display = true,
                        this.message.type = response.data.status,
                        this.message.text = 'Enregistrement réussie !'

                    }
                    console.log(response);
                })
                .catch(function (error) {
                    console.log(error);
                });
            }
        }
    }
</script>
