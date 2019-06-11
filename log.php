<?php
function linLog($conn, $string, $id)
{
    $string = mysqli_real_escape_string($conn, $string);
    $id = mysqli_real_escape_string($conn, $id);
    $query = "INSERT INTO log (string, userid, ts) VALUES ('" . $string . "', '".$id."', '" . time() . "')";

    if ($conn->query($query) === false) {
        $createQuery = "CREATE TABLE IF NOT EXISTS log (id INT AUTO_INCREMENT PRIMARY KEY,
                    string VARCHAR(512) NOT NULL,
                    userid INT(10) NOT NULL ,
                    ts INT NOT NULL)";
        $conn->query($createQuery);
        if ($conn->query($query) === false) {
            exit("Failed to log data, even after attempting to create table");
        }
    }
}