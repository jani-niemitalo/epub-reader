<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>


    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="194x194" href="/favicon-194x194.png">
    <link rel="icon" type="image/png" sizes="192x192" href="/android-chrome-192x192.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="apple-mobile-web-app-title" content="Epub-Reader">
    <meta name="application-name" content="Epub-Reader">
    <meta name="msapplication-TileColor" content="#2b5797">
    <meta name="theme-color" content="#ffffff">
    <meta name="Description" content="Login page.">


    <title>Epub-Reader - <?php require("version.txt") ?></title>
    <meta charset="utf-8"/>
    <meta name="google-signin-client_id"
          content="800988841068-vd3v312eikhgvi9g9k51r3t6c8kn5meu.apps.googleusercontent.com">
    <link rel="stylesheet" href="styles.css">
    <script src="https://apis.google.com/js/platform.js" async defer></script>
    <script>
        function getQueryVariable(variable) {
            var query = window.location.search.substring(1);
            var vars = query.split("&");
            for (var i = 0; i < vars.length; i++) {
                var pair = vars[i].split("=");
                if (pair[0] == variable) {
                    return pair[1];
                }
            }
            return (false);
        }

        function onSignIn(googleUser) {
            if (getQueryVariable("logout")) {
                var auth2 = gapi.auth2.getAuthInstance();
                auth2.signOut().then(function () {
                    window.location = "login.php"
                });

                return;
            }

            var profile = googleUser.getBasicProfile();
            console.log('ID: ' + profile.getId()); // Do not send to your backend! Use an ID token instead.
            console.log('Name: ' + profile.getName());
            console.log('Image URL: ' + profile.getImageUrl());
            console.log('Email: ' + profile.getEmail()); // This is null if the 'email' scope is not present.
            var id_token = googleUser.getAuthResponse().id_token;

            login(id_token, profile);

        }

        function login(input, profile) {

            if (window.XMLHttpRequest) {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp = new XMLHttpRequest();
            } else {
                // code for IE6, IE5
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.open("POST", "login_handler.php", true);
            xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xmlhttp.onload = function() {
                console.log('Signed in as: ' + xmlhttp.responseText);
                window.location = "/";
            };
            //console.log(input);
            var data = ''
            + 'idtoken=' + encodeURIComponent(input)
            + '&name=' + profile.getName()
            + '&email=' + profile.getEmail();

            xmlhttp.send(data);

        }
    </script>
</head>
<body id="login">
<div></div>
<div class="g-signin2" data-onsuccess="onSignIn"></div>
<div style="color: #888888">This web-page will not work if you have disabled specific web technologies such as cookies, redirecting, and or blocked services like Google</div>
</body>

</html>
