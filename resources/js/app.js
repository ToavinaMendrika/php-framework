
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 * 
 * 
 */
import Vue from 'vue';
import App from './components/App.vue'
import VueRouter from 'vue-router'
import router from './components/routes/router'

/*
Vue.component('memo', require('./components/Memo.vue'));
Vue.component('datacar', require('./components/Datacar.vue'));*/

//Vue.component('app', require('./components/App.vue'))
Vue.use(VueRouter)

const app = new Vue({
  el: '#app',
  router,
  components : {
    App
  },  
})
console.log('hello')
