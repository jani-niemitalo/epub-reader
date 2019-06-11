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
include_once ("log.php");

$id_token = $_POST["idtoken"];
//echo "ID Token = ".$id_token;
$CLIENT_ID = "800988841068-vd3v312eikhgvi9g9k51r3t6c8kn5meu.apps.googleusercontent.com";
$client = new Google_Client(['client_id' => $CLIENT_ID]);  // Specify the CLIENT_ID of the app that accesses the backend
$client->addScope("email");
$payload = $client->verifyIdToken($id_token);
if ($payload) {
    $userid = $payload['sub'];
    $userid = mysqli_real_escape_string($conn, $userid);
    $checkIfUser = $conn->query("SELECT * FROM users WHERE google_id = $userid limit 1");
    if (mysqli_num_rows($checkIfUser) > 0) {
        $var = $checkIfUser->fetch_assoc();
        $_SESSION['loggedin'] = true;
        $_SESSION['id'] = $var["id"];
        $_SESSION['perm_lvl'] = $var["permission_lvl"];
        linLog($conn, "User " . $var["email"] . " logged in", $_SESSION["id"]);

    }
    else{
        $email = $_POST["email"];
        $email = mysqli_real_escape_string($conn, $email);
        $password = "";
        $name = $_POST["name"];
        $name = mysqli_real_escape_string($conn, $name);
        $google_id= $userid;
        $sqlInsert = "REPLACE into users (email, password, name, google_id) values ('" .$email. "','-','" .$name. "', '" .$google_id. "');";
        $sqlResult = $conn->query($sqlInsert);
        if ($sqlResult){
            linLog($conn, "User " . $email . "created", $_SESSION["id"]);
        }
        else{
            linLog($conn, "[ERR]". $sqlInsert . " ". $conn->error, $_SESSION["id"]);
        }
    }

    // If request specified a G Suite domain:
    //$domain = $payload['hd'];
} else {
    // Invalid ID token
}

