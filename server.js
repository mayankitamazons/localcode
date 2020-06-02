var app = require('express')();
var server = require('http').Server(app);
var io = require('socket.io')(server);
var debug = require('debug')('Flamer:sockets');
var request = require('request');
var port = process.env.PORT || '3000';


server.listen(port);

io.on('connection', function (socket) {

    debug('new connection established');
    debug('socket.handshake.query.sender', socket.handshake.query.sender);
    
    socket.join(socket.handshake.query.sender);

    socket.emit('connected', 'Connection to server established!');
    socket.on('send message', function(data) {
        data.sender = socket.handshake.query.sender;
        data.time = new Date();
        socket.broadcast.to(data.receiver).emit('message', data);

        request('http://local.kooexchange.com/chat/userApi/saveMessage?sender_id='+data.sender+'&receiver_id='+data.receiver+'&message='+data.message, function (error, response, body) {
            if (!error && response.statusCode == 200) {
                console.log(body) // Show the HTML for the Google homepage. 
            }
        });
    });

    socket.on('disconnect', function(data) {
        debug('disconnect', data);
    });
});