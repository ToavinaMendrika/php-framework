import VueRouter from 'vue-router'
import Register from './../pages/Register'
import Login from './../pages/Login'
import Chat from './../pages/chatroom/chat'
import Search from './../pages/chatroom/components/search'
import ChatBox from './../pages/chatroom/components/chatbox'

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
                component: Chat,
                children: [
                    {
                         path: 'home',
                         name: 'chat_home',
                         component: Search,
                    },
                    {
                         path: ':id',
                         name: 'chat_box',
                         component: ChatBox,
                    }
                 ]
            }

        ]
    }
   
)
export default router