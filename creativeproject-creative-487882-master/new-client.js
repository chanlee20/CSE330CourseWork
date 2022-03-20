const express = require('express');
const mysql = require('mysql');
const session = require('express-session');
const app = express();
const ejs = require("ejs");
const http = require("http").Server(app), fs = require("fs");
const port = 3456;
const path = require('path');
const bodyParser = require('body-parser');
const { get } = require('http');
app.use("/static", express.static('./public/'));
// var engines = require('consolidate');
app.set("view engine", "ejs");
app.engine('html', require('ejs').renderFile);
app.set('view engine', 'html');
app.use(express.static(path.join(__dirname, 'static')));

const users = [];
const messages = [];
const pictures = [];


app.use(bodyParser.json());
app.use(bodyParser.urlencoded({extended: false}));

app.use(session({
  secret: 'secret',
  resave: false,
  saveUninitialized: true, 
  cookie: { secure: false }
}));

var con = mysql.createConnection({
  host: "localhost",
  user: "root",
  password: "wlwl5909",
  database: "creativep"
});

app.use(bodyParser.urlencoded({
    extended: false
}));

app.use(bodyParser.json());

app.get('/', (req, res) => {
    res.sendFile(path.join(__dirname, '/static/client.html'));
  });

app.get('/login', (req, res) => {
  console.log("login page");
  res.sendFile(path.join(__dirname, "/static/login.html"));
});

app.get('/main', (req, res) => {
  console.log("main page");
  let username = req.session.user;
  let user_id = req.session.user_id;
  console.log(username + " is in main.html: " + user_id);
  res.render(__dirname + "/static/main.html", {users: username});
})

app.post('/main', function(req, res){
  let username = req.session.user;
  let user_id = req.session.user_id;
  let image = req.body.profileImage;
  req.session.image = image;
  console.log(username + " is in main2.html " + user_id);
  res.redirect("/main2");

})


app.get('/main2', (req, res) => {
  console.log("profile pic page");
  let username = req.session.user;
  let user_id = req.session.user_id;
  let image = req.session.image
  pictures.push(image);
  console.log(image);
  res.render(__dirname + "/static/main2.html", {users: username, image: image});

})

// app.post('/main', (req, res) => {
//   console.log("hi");
//   let message = req.body.message;
//   let new_message = {message: message};
//   messages.push(new_message);
//   let username = req.session.user;
//   let user_id = req.session.user_id;
//   console.log(username);
//   console.log(message);
//   console.log(user_id);
//     console.log("Connected to MSG");
//     var sql = "insert into Message (user_id, username, message) values ('" + user_id + "' , '" + username + "' , '" + message +"')";
//     con.query(sql, function(err, result) {
//       if(err) throw err;
//       console.log("inserted 1 row of msg");
//       console.log("username : " + username + " message : " + message);
//       res.render(__dirname + "/main.html", {users: username, messages: message});
//       console.log("success");
//     });
  
// });

app.post('/login', (req, res) => {
    let username = req.body.username;
    let message = "";
    let email = req.body.email;
    let new_username = {username: username};
    users.push(new_username);
 

    console.log(username + "  " + email);

      console.log("Connected");
      
      var sql = "insert into users (username, email) values ('" + username + "', '" + email + "')";
      con.query(sql, function(err, result){
        if(err) throw err;
        console.log("inserted 1 row");
        console.log("username : " + username);
        req.session.user = username;
        console.log(req.session.user);
      });

      var sql = "select user_id from users where username = '" + username + "'";
      con.query(sql, function(err, result) {
        if(err) throw err;
        console.log("selected user_id " );
        let data = result;
        let s = JSON.stringify(data[0].user_id);
        req.session.user_id = JSON.parse(s);
        console.log(req.session.user_id);
        res.redirect("/main");
        // res.render(__dirname + "/static/main.html", {users: username, messages: message});
      })
});


app.listen(port, () => {
  console.log(`Example app listening at http://localhost:${port}`);
});

