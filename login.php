<?php
/**
 * Created by PhpStorm.
 * User: aqua
 * Date: 14.4.2019
 * Time: 18:54
 */
require_once("mysqlConnection.php");
require_once 'google-api-php-client-2.2.2/vendor/autoload.php';
session_start();

$id_token = $_POST["idtoken"];
//echo "ID Token = ".$id_token;
$CLIENT_ID = "800988841068-vd3v312eikhgvi9g9k51r3t6c8kn5meu.apps.googleusercontent.com";
$client = new Google_Client(['client_id' => $CLIENT_ID]);  // Specify the CLIENT_ID of the app that accesses the backend
$client->addScope("email");
$payload = $client->verifyIdToken($id_token);
if ($payload) {
    $userid = $payload['sub'];
    $checkIfUser = $conn->query("SELECT * FROM users WHERE google_id = $userid limit 1");
    if (mysqli_num_rows($checkIfUser) > 0) {
        //echo "Exists";
        $_SESSION['loggedin'] = true;
        $_SESSION['id'] = $checkIfUser->fetch_assoc()["id"];
        //create session here for user found in database
    }
    else{
        echo "doesn't";
        //create user here to database
        $email = $_POST["email"];
        $password = "";
        $name = $_POST["name"];
        $google_id= $userid;
        $sqlInsert = "INSERT into users (email, password, name, google_id) values ('" .$email. "','-','" .$name. "', '" .$google_id. "');";
        $sqlResult = $conn->query($sqlInsert);
        if ($sqlResult){
            //echo "[OK]". $sqlInsert;
            //$_SESSION['loggedin'] = true;
        }
        else{
            echo "[ERR]". $sqlInsert;
            echo $conn->error;
        }
    }

    // If request specified a G Suite domain:
    //$domain = $payload['hd'];
} else {
    // Invalid ID token
}

