const http = require("http"),
	fs = require("fs");

const port = 3456;
const file = "client.html";
const server = http.createServer(function(req, resp){
	fs.readFile("client.html", function(err, data){
		if(err) return resp.writeHead(500);
		resp.writeHead(200);
		resp.end(data);
	});
});
server.listen(port);

const socketio = require("socket.io")(http, {
    wsEngine: 'ws'
});
var io = socketio.listen(server);

var rooms = [{'roomName':'lobby'}];
let users={};
var socketId = {};
var n=0;
var inHere = "";

// https://stackoverflow.com/questions/6873607/socket-io-rooms-difference-between-broadcast-to-and-sockets-in
io.sockets.on('connection', function(socket){
	socket.on('username_to_server', function(username){
		var current_User={};
		socketId[username] = socket.id;
		socket.user = username;
		current_User.name = username;
        // joining the lobby, which is a default
		socket.room = 'lobby';
		socket.join('lobby');
		socket.broadcast.to('lobby').emit('update', username + ' has joined the room');
		socket.emit('refresh_Room', rooms, 'lobby');
		current_User.room= socket.room;
		users[username] = current_User;

		n = Object.keys(users).length;
		if(n==1){
			inHere = inHere.concat(users[username].name);
		}
		else{
			inHere = inHere.concat( "  " + users[username].name);
		}			
		io.sockets.in('lobby').emit('who', inHere);
    });
    // https://stackoverflow.com/questions/61116748/how-do-i-make-socket-io-send-emit-message-to-only-one-client
	socket.on('message_to_server', function(data) {
		if(data.message == "/intro"){
			socket.emit("message_to_client", socket.user, {message:'You can join other room and send private message through the right menu!' });
		}
		else if(data.message == "/room"){
			socket.emit("message_to_client", socket.user, {message:'You can talk freely with other people in this room!' });
		}
		else if(data.message == "fuck"){
			socket.emit("message_to_client", socket.user, {message:'Censored!' });
		}
		else if(data.message == "bitch"){
			socket.emit("message_to_client", socket.user, {message:'Censored!' });
		}
		else if(data.message == "/user"){
			var temp_user = "";
			for(var u in users){
			    if(users.hasOwnProperty(u)) {
					n = Object.keys(users).length;
                    if(n==1){
                        temp_user = temp_user.concat(users[u].name);
                    }
                    else{
                        temp_user = temp_user.concat( "  " + users[u].name);
                    }
			    }
		    }
		    socket.emit("message_to_client", socket.user, {message: temp_user});
		}
		else{
			console.log(socket.user+ " : "+ data.message); 
			io.sockets.in(socket.room).emit("message_to_client", socket.user, {message:data.message}); 
		}
	});
	
	socket.on("switchRoom", function(create_room){
        // joining a room
		socket.join(create_room);
		users[socket.user].room = create_room;
		console.log("changing room " + users[socket.user].room);
		var str = "";
		for(var u in users){
			if(users.hasOwnProperty(u)) {
				if(users[u].room == create_room){
					if(n==1){
						str = str.concat(users[u].name);
					}
					else{
						str = str.concat( "  " + users[u].name);
					}
				}
				else{
					var asdf = inHere.replace(socket.user, "");
					io.in(socket.room).emit('who', asdf);
				}
				console.log("join the room room" + str);
			}
		}	
		socket.emit('update', 'you have joined ' +create_room);
		socket.broadcast.to(socket.room).emit('update', socket.user + ' has left the room');
		socket.room = create_room;
		socket.broadcast.to(create_room).emit('update', socket.user + ' has joined the room');
		socket.emit('refresh_Room', rooms, create_room);
		io.sockets.in(create_room).emit('who', str);
	});
	
	
	socket.on("addRoom", function(addRoom){
		rooms.push({roomName:addRoom});
		console.log(rooms);
		io.emit('addRoom', rooms, socket.room);
	});
	
	socket.on("addWithPw", function(addRoom, pw){
		rooms.push({roomName:addRoom, password: pw});
		console.log(rooms);
		io.emit('addRoom', rooms, socket.room);
	});
	
	socket.on("checkPw", function(create_room, pwGuess){
		for(var i = 0 ;i<rooms.length; i++){
			if(rooms[i].roomName == create_room){
				if(rooms[i].password == pwGuess){
					console.log("Were in");
					socket.join(create_room);
					users[socket.user].room = create_room;
					console.log("in switch room " + users[socket.user].room);
					var pass = "";
					for(var thing in users){
						if(users.hasOwnProperty(thing)) {
							if(users[thing].room == create_room){
								if(n==1){
									pass = pass.concat(users[thing].name);
								}
								else{
									pass = pass.concat( " :: " + users[thing].name);
								}
							}
							else{
								var asdf = inHere.replace(socket.user, "");
								io.in(socket.room).emit('who', asdf);
							}
							console.log("is in this new room" + pass);
						}
					}
                    socket.emit('update', 'you have joined ' +create_room);
                    socket.broadcast.to(socket.room).emit('update', socket.user + ' has left the room');
                    socket.room = create_room;
                    socket.broadcast.to(create_room).emit('update', socket.user + ' has joined the room');
                    socket.emit('refresh_Room', rooms, create_room);
					io.sockets.in(create_room).emit('who', pass);
				}
			}
		}
	});
	
	socket.on("dm", function(to, msg){
		var from = socket.user;
		socket.broadcast.to(socketId[to]).emit('private_msg', from, msg);
	});
	
	// kick user
	socket.on("kick", function(who){
		// redirect user to the lobby
		socket.join('lobby');
        users[socket.user].room = 'lobby';
        console.log("changing room " + users[socket.user].room);
        var str = "";
        for(var u in users){
            if(users.hasOwnProperty(u)) {
                if(users[u].room == 'lobby'){
                    if(n==1){
                        str = str.concat(users[u].name);
                    }
                    else{
                        str = str.concat( "  " + users[u].name);
                    }
                }
                else{
                    var asdf = inHere.replace(socket.user, "");
                    io.in(socket.room).emit('who', asdf);
                }
                console.log("join the room room" + str);
            }
        }   
        socket.emit('update', 'you have joined ' +'lobby');
        socket.broadcast.to(socket.room).emit('update', socket.user + ' has left the room');
        socket.room = 'lobby';
        socket.broadcast.to('lobby').emit('update', socket.user + ' has joined the room');
        
        
        socket.emit('refresh_Room', rooms, 'lobby');
        io.sockets.in('lobby').emit('who', str);


	});
	
	socket.on("privateRoom", function(privateRoom){
		for(var i=0; i<rooms.length; i++){
			console.log(rooms[i].hasOwnProperty('password'));
			if(rooms[i].roomName == privateRoom){
				if(rooms[i].hasOwnProperty('password')){
					socket.emit('tryna', privateRoom);
				}
			}
		}
	});
	// https://stackoverflow.com/questions/24463447/socket-io-disconnect-client-by-id
	socket.on("ban", function(user){
		if (io.sockets.sockets[socketId[user]]) {
			io.sockets.sockets[socketId[user]].disconnect(true);
		}
	});


	socket.on('disconnect', function(){
		socket.broadcast.emit('update', socket.user + ' has disconnected');
		socket.leave(socket.room);
		for(var i=0; i<users.length; i++){
			if(users[i].name == socket.user){
				delete users[i];
			}
		}
	});
});
