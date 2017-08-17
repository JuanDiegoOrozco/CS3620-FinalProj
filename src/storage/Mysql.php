<?php

namespace Storage;
use PDO;

// database functions ************************************************
function ConnectToMySQL() {
    try {
        $db = new PDO('mysql:host=localhost; port=3306; dbname=chatroomdb', 'root', '');

        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        return $db;
    }
    catch (PDOException $e){
        echo($e->getMessage());
    }
}
