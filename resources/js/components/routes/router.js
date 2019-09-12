import VueRouter from 'vue-router'
import Register from './../pages/Register'
import Login from './../pages/Login'
import Chat from './../pages/chatroom/chat'

const router = new VueRouter(
    {
        mode: 'history',
        routes: [
            {
                path: '/',
                name: 'login',
                component: Login
            },
            {
                path: '/register',
                name: 'register',
                component: Register
            },
            {
                path: '/chat',
                name: 'chat',
                component: Chat
            }

        ]
    }
   
)
export default router