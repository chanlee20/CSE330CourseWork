// Require the packages we will use:
const http = require("http"),
    fs = require("fs");

const port = 3456;
const file = "client.html";

// Listen for HTTP connections.  This is essentially a miniature static file server that only serves our one file, client.html, on port 3456:
const server = http.createServer(function (req, res) {
    // This callback runs when a new connection is made to our HTTP server.

    fs.readFile(file, function (err, data) {
        // This callback runs when the client.html file has been read from the filesystem.

        if (err) return res.writeHead(500);
        res.writeHead(200);
        res.end(data);
    });
});
server.listen(port);

// Import Socket.IO and pass our HTTP server object to it.
const socketio = require("socket.io")(http, {
    wsEngine: 'ws'
});

let users = {};
let socketId = {};
const user_array = [];
let rooms = ['lobby'];
let roomscreator = {};
let privrooms = {}; //roomname: pwd
let privrooms_array = [];
let bannedusers = {} //roomname: list of banned

// Attach our Socket.IO server to our HTTP server to listen
const io = socketio.listen(server);
io.sockets.on("connection", function (socket) {
    // This callback runs when a new Socket.IO connection is established.

    socket.on('adduser', function(name){
        user_array.push(name);
        let id = socket.id;
        users[name] = {
            user: name,
            room: ""
        };

        socketId[name] = {
            ID: id
        };
        console.log("id: " + id);
        users[name]["room"] = "lobby";
        socket.user = name;
        socket.room = "lobby";
        socket.join("lobby");
        socket.emit('updateRooms', rooms);
        io.sockets.in("lobby").emit("refresh", name + " has joined " + socket.room);
        let user = socket.user;
        users[user]["name"] = user;
        let listofUsers = "";
        for (let i in users){
            if(users[i]["room"] == "lobby"){
                if(i != null){
                    listofUsers += i + " "; 
                }
            }
        }
        console.log("list of users: " + listofUsers);
        io.in("lobby").emit("updateUsers", listofUsers);

    });

    socket.on('checkpwd', function(data){
        console.log("checkpwd");
        let user = socket.user;
        let isbanned=false;

        let banneduser=""; let idd = "";
        for (let i in bannedusers[data["roomname"]]) {
            console.log("banned user (joinroomtoserver) " + bannedusers[data["roomname"]][i]);
            if (bannedusers[data["roomname"]][i]===user) {
                banneduser= bannedusers[data["roomname"]][i];
                idd = socketId[banneduser]["ID"];
                isbanned = true;
            }
        }
       
       
        if (isbanned===false) {
        if (data['pwd']===privrooms[data['roomname']]) {
            let user = socket.user;
            socket.leave(socket. room);
            socket.join(data["roomname"]);
            users[user]["name"] = user;
            users[user]["room"] = data["roomname"];
            console.log(users[user]["name"] + " " + users[user]["room"]);
            let listofUsers = "";
            let prevUsers = "";
            for (let i in users){
                if(users[i]["room"] == data["roomname"]){
                    if(i != null){
                        listofUsers += i + " "; 
                    }
                }
                else if(users[i]["room"] == socket.room){
                    prevUsers += i + " ";
                    console.log(socket.room + " ll ");
                }
            }
            let id = socketId[socket.user]["ID"];
            io.to(id).emit("alter_current_room", data["roomname"]);
            console.log("rooms: " + rooms);
            console.log("list of users: " + listofUsers + " in " + data["roomname"]);
            console.log("list of users: " + prevUsers + " in " + socket.room);
            // socket.to(socket.room).emit("updateUsers", prevUsers);
            // socket.to(socket.room).emit("updateUsers", listofUsers);
            io.in(data["roomname"]).emit("refresh", user + " has joined " + data["roomname"]);
            io.in(socket.room).emit("refresh", user + " has left " + socket.room);
            io.sockets.in(socket.room).emit("updateUsers", prevUsers);
            socket.room = data["roomname"];
            io.sockets.in(data["roomname"]).emit("updateUsers", listofUsers);
        
            console.log("current room for " + user + ":" + data["roomname"]);
        } else {
            console.log("wrong password");
        }} else {
            io.to(idd).emit("ban_to_client2", {rn: data["roomname"]});
        }
    });

    socket.on('pm_to_server', function(data) {
        let pm_to = data["pm_to"];
        let pm = data["pm"];
        let pm_from = socket.user;
        
        let id = socketId[pm_to]["ID"];
        let id2 = socketId[pm_from]["ID"];
        io.to(id).emit("pm_to_client", {pm_to: pm_to, pm: pm, pm_from: pm_from});
        io.to(id2).emit("pm_to_client", {pm_to: pm_to, pm: pm, pm_from: pm_from});
    }) 

    socket.on('message_to_server', function (data) {
        // This callback runs when the server receives a new message from the client.
        let user = socket.user;
        console.log("messaged in : " + socket.room);
        console.log(user + ": " + data["message"]); // log it to the Node.JS output
        io.sockets.in(socket.room).emit("message_to_client", { user: user, message: data["message"] }) // broadcast the message to other users
    });

    socket.on('anonmessage_to_server', function (data) {
        // This callback runs when the server receives a new message from the client.
        let user = socket.user;
        console.log("messaged in : " + socket.room);
        console.log(user + ": " + data["message"]); // log it to the Node.JS output
        io.sockets.in(socket.room).emit("anonmessage_to_client", {message: data["message"] }) // broadcast the message to other users
    });

    socket.on('joinRoom_to_server', function(data){
        let user = socket.user;
        let listofUsers = "";
        let prevUsers = "";
        let isbanned=false;

        let banneduser=""; let id = "";
        for (let i in bannedusers[data["roomname"]]) {
            console.log("banned user (joinroomtoserver) " + bannedusers[data["roomname"]][i]);
            if (bannedusers[data["roomname"]][i]===user) {
                banneduser= bannedusers[data["roomname"]][i];
                id = socketId[banneduser]["ID"];
                isbanned = true;
            }
        }
       
       
        if (isbanned===false) {
        socket.leave(socket.room);
        socket.join(data["roomname"]);
        users[user]["name"] = user;
        users[user]["room"] = data["roomname"];
        console.log(users[user]["name"] + " " + users[user]["room"]);

        for (let i in users){
            if(users[i]["room"] == data["roomname"]){
                if(i != null){
                    listofUsers += i + " "; 
                }
            }
            else if(users[i]["room"] == socket.room){
                prevUsers += i + " ";
                console.log(socket.room + " ll ");
            }
        }
        let id = socketId[socket.user]["ID"];
        io.to(id).emit("alter_current_room", data["roomname"]);
        console.log("rooms: " + rooms);
        console.log("list of users: " + listofUsers + " in " + data["roomname"]);
        console.log("list of users: " + prevUsers + " in " + socket.room);
        // socket.to(socket.room).emit("updateUsers", prevUsers);
        // socket.to(socket.room).emit("updateUsers", listofUsers);
        io.in(data["roomname"]).emit("refresh", user + " has joined " + data["roomname"]);
        io.in(socket.room).emit("refresh", user + " has left " + socket.room);
        io.sockets.in(socket.room).emit("updateUsers", prevUsers);
        socket.room = data["roomname"];
        io.sockets.in(data["roomname"]).emit("updateUsers", listofUsers);
        
        console.log("current room for " + user + ":" + data["roomname"]);
        } else {
            console.log("user has been banned");
            io.to(id).emit("ban_to_client2", {rn: data["roomname"]});
        }
    });

    socket.on('invite_toserver', function(data) {
        let to = data["to"];
        let room = data["room"];
        let from = socket.user;
        let id = socketId[to]["ID"];
        let cur_room = socket.room;
        console.log("cur_room" + cur_room + " room: " + room);
        io.to(id).emit("invite_toclient", {to: to, room: room, from: from, cur_room: cur_room});

    })

    socket.on('roomname_to_server', function (data) {
        let rn = data["roomname"];
        let user = socket.user;
        data['creator'] = user;
        rooms.push(rn);
        
        roomscreator[rn] = user;
        bannedusers[rn] = new Array("");

        console.log(rooms);
        console.log("roomscreator" + roomscreator);
        io.emit('listRooms', rooms);
    });

    socket.on('privroom_to_server', function (data) {
        let privrn = data["privroomname"];
        let pwd = data["privpwd"];
        privrooms[privrn] = pwd;
        let user =socket.user;

        roomscreator[privrn] = user
        bannedusers[privrn] = new Array([data["user_to_ban"]]);
      
        privrooms_array.push(privrn);
        console.log("privrooms:" + privrooms);
        console.log("privrooms list:" + privrn);

        io.emit('listPrivRooms', privrooms_array);
    });

 
    socket.on('kickuser', function (data) {
        let sendto = "lobby";
        let kickeduser = data["kickeduser"];
        let curroom = data['room'];
        let id = socketId[kickeduser]["ID"];
        let from = socket.user;
        let roomcreator = roomscreator[curroom];
        // let curroom = socket.room;
        console.log("room creator" + roomcreator);
        console.log("kick user" + kickeduser + " to " + sendto)
        io.to(id).emit("kick_to_client", {sendto: sendto, room: curroom, from: from, creator: roomcreator});
    });
   

    socket.on('banuser', function (data) {
      
        let banneduser = data["banneduser"];
        let room = data["room"];
        let roomcreator = roomscreator[room];
        let from = socket.user;
        let id = socketId[banneduser]["ID"]

        if (from === roomcreator) {
             bannedusers[room].push(banneduser);
             console.log("bannedusers[room]:" + bannedusers[room]);
             io.to(id).emit("ban_to_client", {sendto: "lobby", room: room, from: from, creator: roomcreator});
        } else {
            console.log("failed to ban user; not creator")
            io.to(socketId[socket.user]["ID"]).emit("notcreator", {rn: room});
        }

       
    });
});