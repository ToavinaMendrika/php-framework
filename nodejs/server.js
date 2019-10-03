const express = require('express')
const app = express()
const cors = require('cors')
const socket = require('socket.io')

app.use(cors())

let server = app.listen(3000, _ =>{
    console.log('Websocket Listening on port ' + 3000)
})

const io = socket(server)
io.on('connection', (socket) => {
    console.log('Un utilisateur est connecter')

    socket.on('disconnect', function(){
        console.log('user disconnected')
    })

    socket.on('chat message', (msg) => {
        io.emit('chat message', msg)
    })
})