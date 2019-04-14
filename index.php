<!DOCTYPE html>
<html>
<head>
    <title>Epub-Reader - <?php require("version.txt") ?></title>
    <meta charset="utf-8"/>
    <meta name="google-signin-client_id" content="800988841068-vd3v312eikhgvi9g9k51r3t6c8kn5meu.apps.googleusercontent.com">
    <link rel="stylesheet" href="styles.css">
    <script src="https://apis.google.com/js/platform.js" async defer></script>
    <script>
        function getQueryVariable(variable)
        {
            var query = window.location.search.substring(1);
            var vars = query.split("&");
            for (var i=0;i<vars.length;i++) {
                var pair = vars[i].split("=");
                if(pair[0] == variable){return pair[1];}
            }
            return(false);
        }

        function onSignIn(googleUser) {
            if (getQueryVariable("logout")) {
                var auth2 = gapi.auth2.getAuthInstance();
                auth2.signOut().then(function () {
                    window.location = "http://localhost:8888"
                });

                return;
            }

            var profile = googleUser.getBasicProfile();
            console.log('ID: ' + profile.getId()); // Do not send to your backend! Use an ID token instead.
            console.log('Name: ' + profile.getName());
            console.log('Image URL: ' + profile.getImageUrl());
            console.log('Email: ' + profile.getEmail()); // This is null if the 'email' scope is not present.
            window.location = "http://localhost:8888/library.php"
        }
    </script>
</head>
<body id="login">
<div class="g-signin2" data-onsuccess="onSignIn"></div>

</body>

</html>
