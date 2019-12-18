const express = require('express')
const app = express()
const cors = require('cors')
const socket = require('socket.io')

app.use(cors())

let server = app.listen(3000, _ =>{
    console.log('Websocket Listening on port ' + 3000)
})

const io = socket(server)

let connectedUsers = []



io.on('connection', (socket) => {
    console.log('Un utilisateur est connecter')

    socket.on('disconnect', function(){
        console.log('user disconnected: ')
    })

    socket.on('user_connection', (user) => {
        console.log('user connection: ' + user.pseudo)
        connectedUsers.push(user)
        
        io.emit('userConnected', connectedUsers)
    })

    socket.on('new_message', message =>{
        io.emit('userConnected', connectedUsers)
    })

    socket.on('user_logout', (user) => {
        connectedUsers = connectedUsers.filter(function(connectedUser,){
            return connectedUser.id != user.id
        });
        io.emit('userConnected', connectedUsers)
    })
})

app.get('/user/connected', (req, res) => res.send(connectedUsers))