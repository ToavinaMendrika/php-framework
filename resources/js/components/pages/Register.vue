<template>

    <div class="modal is-active">
        <div class="modal-background"></div>
        <div class="modal-content">
            <!-- Any other Bulma elements you want -->
            <div class="modal-card">
                <header class="modal-card-head">
                    <p class="modal-card-title">Incription</p>
                    <article :class="'message ' + message.type" v-if="message.display">
                        <div class="message-body">
                            {{message.text}}
                        </div>
                    </article>
                </header>
               
                <section class="modal-card-body">
                    <form action="" methods="post" @submit.prevent="register">
                        <div class="field">
                            <label class="label">Nom</label>
                            <div class="control">
                                <input class="input" type="text" placeholder="Nom">
                            </div>
                        </div>
                        <div class="field">
                            <label class="label">Prenom</label>
                            <div class="control">
                                <input class="input" type="text" placeholder="¨Prenom">
                            </div>
                        </div>
                        <div class="field">
                            <label class="label">Pseudo</label>
                            <div class="control">
                                <input class="input" type="text" placeholder="¨Prenom" v-model="user.username">
                            </div>
                        </div>
                        <div class="field">
                            <label class="label">Date de naissance</label>
                            <div class="control">
                                <input class="input" type="date" placeholder="¨Prenom">
                            </div>
                        </div>
                        <div class="field">
                            <label class="label">E-mail</label>
                            <div class="control">
                                <input class="input" type="text" placeholder="E-mail" v-model="user.email">
                            </div>
                        </div>
                        <div class="field">
                            <label class="label">Mot de passe</label>
                            <div class="control">
                                <input class="input" type="password" placeholder="Entrer votre mot de passe"
                                    v-model="user.pass">
                            </div>
                            <div class="field">
                                <label class="label">Confirmer mot de passe</label>
                                <div class="control">
                                    <input class="input" type="password" placeholder="Confirmer votre mot de passe"
                                        v-model="user.confirmPass">
                                </div> <br><br>
                                <div class="buttons">
                                    <button :class="'button is-primary '+  state" type="submit">S'inscrire</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </section>
                <footer class="modal-card-foot">
                    <button class="button" id="closeModal" @click.prevent="closeModal">Annuler</button>
                </footer>
            </div>
        </div>
    </div>

</template>
<script>
    import axios from 'axios'
    const qs = require('querystring')
    export default {
        data() {
            return {
                user: {
                    username: '',
                    pass: '',
                    email: '',
                    confirmPass: ''
                },
                message: {
                    display: false,
                    type: '',
                    text: ''
                },
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
        methods: {
            register() {
                this.state = 'is-loading'
                if(this.user.username === ''){
                    this.message.display = true,
                    this.message.type = 'is-danger',
                    this.message.text = 'Tous les champs sont requis'
                    
                    this.state = ''
                }
                else if (this.user.pass != this.user.confirmPass || this.user.pass == ''){
                    this.message.display = true,
                    this.message.type = 'is-danger',
                    this.message.text = 'Le mot de passe ne correspond pas'
                    this.state = ''
                }
                else{
                    axios.post('/user/register', qs.stringify(this.user), {
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded'
                            }
                        })
                        .then((response) => {
                            this.state = ''
                            if (response.data.status == 'success') {
                                this.message.display = true,
                                this.message.type = 'is-success',
                                this.message.text = 'Bienvenue sur simple chat, connectez vouz pour comencer'
                                this.username = ''
                                this.pass = ''
                                this.confirmPass = ''

                            }
                            else{
                                this.message.display = true,
                                this.message.type = 'is-danger',
                                this.message.text = response.data.token
                                this.username = ''
                                this.pass = ''
                                this.confirmPass = ''
                            }      
                        })
                        .catch(function (error) {
                            console.log(error);
                        });
                }
                
            },
            closeModal() {
                this.$parent.showRegister = false
            }
        }
    }
</script>
