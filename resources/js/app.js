
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 * 
 * 
 */
import Vue from 'vue'
import VueRouter from 'vue-router'
import router from './components/routes/router'
import VueSocketIO from 'vue-socket.io'
import store from './store/discussionStore'
//import App from './components/App'

/*
Vue.component('memo', require('./components/Memo.vue'));
Vue.component('datacar', require('./components/Datacar.vue'));*/

//Vue.component('app', require('./components/App.vue'))
Vue.use(VueRouter)
Vue.use(new VueSocketIO({
  debug: true,
  connection: 'http://localhost:3000',
  vuex: {
      store,
      actionPrefix: 'SOCKET_',
      mutationPrefix: 'SOCKET_'
  }
}))

//const store = require('./store/discussionStore')
const app = new Vue({
  router,
  store : store,
  render: h => h(require('./components/App').default)  
}).$mount('#app')
console.log('hello')
