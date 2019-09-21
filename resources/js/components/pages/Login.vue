<template>
    <div>
        <section class="hero is-primary">
            <div class="hero-head">
                <nav class="navbar">
                    <div class="container">
                        <div class="navbar-brand">
                            <a class="navbar-item">
                                <img src="/img/Logo.png" alt="Logo">
                            </a>
                            <span class="navbar-burger burger" data-target="navbarMenuHeroB">
                                <span></span>
                                <span></span>
                                <span></span>
                            </span>
                        </div>
                        <div id="navbarMenuHeroB" class="navbar-menu">
                            <div class="navbar-end">
                                <a class="navbar-item is-active">
                                    Accueil
                                </a>
                                <a class="navbar-item" @click.prevent="showModal">
                                    Inscription
                                </a>
                            </div>
                        </div>
                    </div>
                </nav>
            </div>
            <div class="hero-body">
                <div class="container">
                    <h1 class="title">
                        Simple chat
                    </h1>
                    <h2 class="subtitle">
                        Discutez simplement avec vos amis et familles
                    </h2>
                    <div class="level">
                        <div class="level-left">
                            <img src="img/image-illustration.png" width="301px" height="434px">
                        </div>
                        <div class="level-right">
                            <div class="box" style="margin-top:-6em;width: 21em; min-height: 25em; border-radius: 10%">
                                <div class="content">                    
                                    <form action="" method="post" @submit.prevent="login">
                                        <h3 align="center">Connexion</h3> <br>
                                        <article class="message is-danger" v-if="message.display">
                                            <div class="message-body">
                                                {{message.text}}
                                            </div>
                                        </article>
                                        <div class="field">
                                            <label class="label">E-mail</label>
                                            <div class="control">
                                                <input class="input" type="email" placeholder="Entrer votre e-mail" v-model="user.email">
                                            </div>
                                        </div>
                                        <div class="field">
                                            <label class="label">Mot de passe</label>
                                            <div class="control">
                                                <input class="input" type="password"
                                                    placeholder="Entrer votre mot de passe" v-model="user.pass">
                                            </div>
                                        </div> <br>

                                        <div class="buttons">
                                            <button :class="'button is-primary is-fullwidth '+  state" type="submit">Connexion</button>
                                        </div>
                                        <p>Pas encore de compte? Inscrivez-vous <a href="#" id="insc" @click.prevent="showModal">ici</a></p>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <register v-if="showRegister"/>
        </section>
        <footer class="footer">
            <div class="content has-text-centered">
                <p>
                    <strong> Copyright 2019 : L2 IDEV | Groupe 3 </strong>
                </p>
            </div>
        </footer>
    </div>
</template>
<script>
    import axios from 'axios'
    import Register from './Register'
    const qs = require('querystring')
    export default {
        data() {
            return {
                user: {
                    pass: '',
                    email: '',
                },
                message: {
                    display: false,
                    type: '',
                    text: ''
                },
                showRegister: '',
                state: ''
            }
        },
        mounted() {
            if (window.localStorage.getItem('token') !== null) {
                this.$router.push({
                    name: 'chat_home'
                })
            }
        },
        components: {
            Register
        },
        methods: {
            login() {
                this.message.display = false
                this.state = 'is-loading'
                console.log('register: ', qs.stringify(this.user));
                axios.post('/user/login', qs.stringify(this.user), {
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        }
                    })
                    .then((response) => {
                        this.state = ''
                        if (response.data.status == 'error') {
                            this.message.display = true,
                                this.message.type = response.data.status,
                                this.message.text = response.data.token
                        } else {
                            window.localStorage.setItem('token', response.data.token)
                            this.$router.push({
                                name: 'chat_home'
                            })
                        }

                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            showModal(){
                this.showRegister = true
            }
        }
    }
</script>
