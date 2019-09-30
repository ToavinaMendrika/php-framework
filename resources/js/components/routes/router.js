import VueRouter from 'vue-router'
import Register from './../pages/Register'
import Login from './../pages/Login'
import Chat from './../pages/chatroom/chat'
import Search from './../pages/chatroom/components/search'
import ChatBox from './../pages/chatroom/components/chatbox'
import Contacts from './../pages/chatroom/components/contacts'
import User from './../pages/chatroom/components/user'

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
                    },
                    {
                        path: 'contacts',
                        name: 'chat_contacts',
                        component: Contacts,
                    },
                    {
                        path: 'user/:id',
                        name: 'chat_user',
                        component: User
                    }
                    
                 ]
            }

        ]
    }
   
)
export default router