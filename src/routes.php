<?php

namespace Storage;
use App\domain\User;

require __DIR__ . '/domain/ChatRoom.php';
require __DIR__ . '/domain/Message.php';
require __DIR__ . '/domain/User.php';
//require __DIR__ . '/storage/Mysql.php';

/*
GET     /todos              Retrieves all todos
GET     /todos/1            Retrieve todoa with id=1
GET     /todos/search/bug   Search for todos with 'bug' in the name
POST    /todos              Add new todos
PUT     /todos/1            Updated todos with id=1
DELETE  /todos/1            Delete todos with id=1
 */

$app->get('/todos', function ($req, $resp) {
    $st = $this->db->prepare("SELECT * FROM tasks ORDER BY task");
    $st->execute();
    $todos = $st->fetchAll();
    return $resp->withJson($todos);
});

$app->get('/todos/{id}', function ($req, $resp, $args) {
    $st = $this->db->prepare("SELECT * FROM tasks WHERE id=:id");
    $st->bindParam("id", $args['id']);
    $st->execute();
    $todos = $st->fetchObject();
    return $resp->withJson($todos);
});

$app->get('/todos/search/{target}', function ($req, $resp, $args){
    $st = $this->db->prepare("SELECT * FROM tasks ORDER BY task");
    $st->execute();
    $todos = $st->fetchAll();
    foreach($todos as $todo) {
        if (strpos($todo['task'], $args['target']) != FALSE) {
            return $resp->withJson($todo); // Only returns the first one
        } 
    }
    return $resp->withStatus(404);
});

$app->post('/todos', function ($req, $resp, $args) {
    $body = $req->getBody();
    $json = json_decode($body, true);
    if ($json === NULL) {
        return $resp->withStatus(400);
    }
    $sql = "INSERT INTO tasks (task, status) VALUES (:task, :status)";
    $st = $this->db->prepare($sql);
    $st->bindParam(":task", $json['task']);
    $st->bindParam(":status", $json['status']);
    $st->execute();
    $id = $this->db->lastInsertId();
    $uri = $req->getRequestTarget(); 
    $url = $req->getUri()->getScheme() . "://" . $req->getUri()->getHost() . $uri . "/" . $id;
    $nResp = $resp->withHeader('Location', $url);
    return $nResp->withStatus(201);
});

$app->delete('/todos/{target}', function ($req, $resp, $args) {
    $sql = "DELETE FROM tasks WHERE id=:id";
    $st = $this->db->prepare($sql);
    $st->bindParam(":id", $args['target']);
    $st->execute();
    if ($st->rowCount() === 0) {
        return $resp->withStatus(404);
    }
    return $resp->withStatus(204);
});

$app->get('/chatrooms', function ($req, $resp, $args) {
    echo('<!docktype html>');
    echo('<head><h1>Juan Orozco: Chat Server</h1></head>');
    echo('<br>');
    echo('<br>');
    echo('<h2>User information</h2>');
    echo('<h3>-----------------------------------------------------</h3>');
    echo('<br>');
    echo('
    <form name="user">
        Alias: <br>
        <input type="text" name="alias" required><br>
        Email:<br>
        <input type="email" name="email" required>
        <br>
    </form>');
    echo('<br>');
    echo('<h2>Current Chatrooms</h2>');
    echo('<h3>-----------------------------------------------------</h3>');
    echo('<br>');
    echo('<button type="button">Create Chatroom</button>');
    echo('<br>');
    echo('<br>');
    $db = ConnectToMySQL();
    $sql = 'SELECT * FROM chatrooms';

    echo('<script type="text/javascript">
            function validateForm(valueG) {
                var x = document.forms["user"]["alias"].value;
                var y = document.forms["user"]["email"].value;
                if (x == "" || y == "") 
                {
                    alert("User Information must be filled out");
                    return false;
                }
                else
                {
                    var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
                    var userformat = /^([a-zA-Z0-9 _-]+)$/;
                    if(mailformat.test(y))  
                    {   
                        if(userformat.test(x)){
                            document.location.href = document.location.href + "/"+valueG+"/"+x+";"+y;
                        
                            return true;
                        }
                        else{
                            alert("You have entered an invalid alias name. It Only allows a-z, A-Z, 0-9, Space, Underscore and dash.");
                            return false;
                        }
                    }  
                    else  
                    {  
                        alert("You have entered an invalid email address!");
                        return false;  
                    }  
                }
            }
        </script>');

    foreach($db->query($sql)as $row){

        //$location = __DIR__.'/public/index.php/chatrooms/' . $row['name'];

        //echo($location);
        $test = "'".$row['name']."'";
        //echo('<button type="button" onclick="location.href =' ". $location ." '">'.$row['name'].'</button>');
        echo('<button type="button" onclick="validateForm('. $test .')">' . 'Join:' . $row['name'] . '</button>');
        //echo('<input type="hidden" value="Join: '.$row['name'].'" href="/CS3620-FinalProj/src/public/index.php/chatrooms/' . $row['name'] . '">');
        //echo('<input type="submit" value="" href="">');
        echo('<br>');
        echo('<br>');
    }
});

$app->get('/chatrooms/{chatroomname}/{user}', function ($req, $resp, $args) {

    $name = $req->getAttribute('chatroomname');
    $user = $req->getAttribute('user');
    $information = explode(";",$user);
    $userformat = '/^([a-zA-Z0-9 _-]+)$/';
    if(!filter_var($information[1], FILTER_VALIDATE_EMAIL))
    {
        throw new $this->Exception('Email invalid');
    }
    if(!preg_match($userformat,$information[0])){
        throw new $this->Exception('User invalid');
    }

    $userAlias = $information[0];
    $userEmail = $information[1];

    $currentUser = new User();
    $currentUser->SetAlias($userAlias);
    $currentUser->SetEmail($userEmail);

    $db = ConnectToMySQL();
    $sqlFindUserByEmail = 'SELECT COUNT(*) AS total FROM users WHERE email LIKE "'.$userEmail.'"';
    $sqlGetUserID = 'SELECT userID as id FROM chatroomdb.users WHERE email like "'.$userEmail.'"';
    $sqlGetChatRoomID = 'SELECT chatroomID as id FROM chatroomdb.chatrooms WHERE name Like "'.$name.'"';
    $sqlSendMessage = 'INSERT INTO chatroomdb.messages (senderID,date,chatroomID,message) VALUES (2,date(now()),1, "Test message 3");';// needs update

    $number = '';
    $chatroomID = '';
    $userID = '';
    foreach ($db->query($sqlFindUserByEmail) as $row){
        $number = $row['total'];
    }
    foreach ($db->query($sqlGetChatRoomID) as $row){
        $chatroomID = $row['id'];
    }
    foreach ($db->query($sqlGetUserID) as $row){
        $userID = $row['id'];
    }
    $sqlInsertUser = 'INSERT INTO chatroomdb.users (alias,email,chatroomID,isActive) VALUES ("'.$userAlias.'","'.$userEmail.'",'.$chatroomID.', 1)';
    $sqlUpdateChatRoomID = 'UPDATE chatroomdb.users SET chatroomID = '.$chatroomID.' WHERE userID = '.$userID;
    if(strcmp($number,"0") == 0)
    {
        $db->query($sqlInsertUser);// Insert user
    }
    else
    {// update chatroom ID for the user
        $db->query($sqlUpdateChatRoomID);// Insert user
    }

    $sqlGetAllMessages = 'SELECT m.messageID, u.alias, m.date, m.chatroomID, m.message 
    FROM chatroomdb.messages m INNER JOIN chatroomdb.users u on u.userID = m.senderID WHERE m.chatroomID = ' . $chatroomID;

    //$resp->getBody()->write('Chatroom: ' . $name. ', Alias: ' . $information[0] . ', Email: ' . $information[1].'Number is : '.$number);
    //echo('Chatroom: ' . $name. ', Alias: ' . $information[0] . ', Email: ' . $information[1].'previous : '.$number);
    //return $resp;

    echo('<!docktype html>');
    echo('<head><h1>Welcome to '.$name.' Chatroom</h1></head>');
    echo('<br>');
    echo('<br>');
    echo('<h2>Messages</h2>');
    echo('<h3>-----------------------------------------------------</h3>');
    echo('<br>');
    foreach ($db->query($sqlGetAllMessages) as $row){
        echo('<p>User: '.$row['alias'].'</p>');
        echo('<p> Message: '.$row['message'].'</p>');
        echo('<p> Date: '.$row['date'].'</p>');
        echo('<br>');
    }

    echo('<form name="message">');
    echo('Enter message here <br>');
    echo('<input type="text" name="messagetext">');
    echo('<br>');
    echo('<input type="submit" value="Send">');
    echo('<br>');
    echo('</form>');
});