<?php
require_once("mysqlConnection.php");



$usersQueries = array();
$dataQueries = array();



for ($i=1; $i < 5000; $i++) {
    $min = 0;
    $max = 10000;
    $randNmbr = rand ( $min , $max );
    echo $randNmbr;
    array_push($usersQueries,
    "   INSERT INTO `users` (`id`,      `email`,                    `password`, `name`  )
        VALUES              ('".$i."',  '".$randNmbr."@gmail.com', '123123',   'Seppo' );
    ");
    $quert_var1 =
    "   REPLACE INTO `users` (`id`,      `email`,                    `password`, `name`  )
        VALUES              ('".$i."',  '".$randNmbr."@gmail.com', '123123',   'Seppo' );
    ";
    qFN($quert_var1);
    for ($n=0; $n < 50; $n++) {
        $min = -100;
        $max = 100;
        $bookmin = 24866-396;
        $bookmax = 24866;
        $randTime = time() +  rand ( $min , $max );
        $bookID = rand ( $bookmin , $bookmax );
        echo $randTime;
        array_push($dataQueries,
        "   INSERT INTO `bookmarks` (`user_id`, `book_id`,      `location`,     `ts`)
            VALUES                   ('".$i."',  '24852',        '123123',       '".$randTime."' );
        ");
        $query_var2 =
        "   REPLACE INTO `bookmarks` (`user_id`, `book_id`,      `location`,     `ts`)
            VALUES                   ('".$i."',  '".$bookID."',        '123123',       '".$randTime."' );
        ";
        qFN($query_var2);
    }

}

function qFN($query)
{
    global $conn;
    require_once("mysqlConnection.php");

    $res = $conn->query($query);
    //echo $queries[$i]. "<br>";
    echo $res ? "[OK] " . $query : "[ERR] " . $conn->error;
    echo "<br/>";
}
