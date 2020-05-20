var app = require('express')();
var http = require('http').Server(app);
var io = require('socket.io')(http);

io.on('connection', function(socket){
    console.log('new client connected');
    socket.on('disconnet', function(){
        console.log('a client disconnect'); 
    })
    socket.on('notif',function(msg){
        console.log('message: '+msg.name+ ': ' + msg.message);
        io.emit('notif', {name: msg.name, message: msg.message});
    })
});

http.listen(3000, function(){
  console.log('listening on *:3000');
});