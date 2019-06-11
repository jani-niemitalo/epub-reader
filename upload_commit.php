<?php

require ("vendor/autoload.php");
include_once ("cover.php");
include_once ("enumToInt.php");
session_start();
require_once ("session.php");
include_once ("epub.php");
include_once ("mime2ext.php");
include_once ("log.php");
include_once ("optimizeImage.php");


if (enumToInt($_SESSION["perm_lvl"]) < 1) {
    header("Location: library.php");
    exit("Permission Denied");
}
$userID = $_SESSION["id"];

function getDirContents($dir, &$results = array())
{
    $files = scandir($dir);

    foreach ($files as $key => $value) {
        $path = $dir . DIRECTORY_SEPARATOR . $value;
        if (!is_dir($path)) {
            $results[] = $path;
        } else if ($value != "." && $value != "..") {
            getDirContents($path, $results);
            $results[] = $path;
        }
    }

    return $results;
}

$checkQuery = "SELECT uploader FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = library AND TABLE_NAME = books";
$result1 = $conn->query($checkQuery);
//echo($booksQueryResult1);

if ($result1 == "") {
    $checkQuery2 = "ALTER TABLE books add COLUMN `uploader` int(10)";
    $booksQueryResult1 = $conn->query($checkQuery2);
}


foreach (getDirContents('uploads/' . $userID) as $v) {
    if (strripos($v, '.epub', -0)) {
        if ($v == "") {
            continue;
        }
        $target_dir = "ebook/userfiles/" . $userID . "/";
        try {
            if (!is_dir($target_dir))
                mkdir($target_dir, 0755, true);
        } catch (Exception $e) {
            exit($e);
        }

        if (rename($v, $target_dir . basename($v))) {

            $v = $target_dir . basename($v);

            $epub = new EPub($v);
            $var = $epub->ISBN();
            $img = $epub->Cover();
            $title = $epub->Title();
            $author = "";

            foreach ($epub->Authors() as $auth) {
                $author .= $auth . " ";
            }
            $series = "";
//if ($var == "") {
//        echo "Found empty ISBN, skipping: " . $v . "<br/>";
//        continue;
//    }
            $query_title   = mysqli_real_escape_string($conn, $title);
            $query_author   = mysqli_real_escape_string($conn, $author);
            $query_isbn   = mysqli_real_escape_string($conn, $var);
            $query_series   = mysqli_real_escape_string($conn, $series);
            $query_path   = mysqli_real_escape_string($conn, $v);

            $sqlSelect = "SELECT * from books where path = '".$query_path."'";
            $sqlSelect = $conn->query($sqlSelect);
            if (mysqli_num_rows($sqlSelect) > 0){
                $fetch = $sqlSelect->fetch_assoc();
                if ($fetch["isbn"] == NULL)
                    $query_isbn   = mysqli_real_escape_string($conn, $var);
                else
                    $query_isbn = $fetch["isbn"];
                if ($fetch["title"] == NULL)
                    $query_title   = mysqli_real_escape_string($conn, $title);
                else
                    $query_title = $fetch["title"];
                if ($fetch["isbn"] == NULL)
                    $query_author   = mysqli_real_escape_string($conn, $author);
                else
                    $query_author = $fetch["author"];
                if ($fetch["isbn"] == NULL)
                    $query_series   = mysqli_real_escape_string($conn, $series);
                else
                    $query_series = $fetch["series"];



                $sqlInsert = "  UPDATE books SET 
                    isbn='".$query_isbn."', 
                    path='".$query_path."', 
                    title='".$query_title."', 
                    author='".$query_author."', 
                    series='".$query_series."', 
                    permission_lvl='uploader', 
                    uploader='".$userID."'
                    WHERE id='".$fetch["id"]."'" ;
            }
            else{
                $sqlInsert = "  INSERT INTO books (isbn, path, title, author, series, permission_lvl, uploader) VALUES ('" . $query_isbn . "', '" . $query_path . "', '" . $query_title . "','" . $query_author . "','" . $query_series . "', 'uploader' , " . $userID . ")";

            }
            //echo $sqlInsert . "<br>";
            $sqlResult = $conn->query($sqlInsert);
            if ($sqlResult) {
                if ($img['found']) {
                    //header('Content-Type: '.$img['mime']);
                    //echo $img['found'];
                    //echo "V:___".$v . "<br>";
                    $path_var = mysqli_real_escape_string($conn, $v);
                    //echo "V_s:_".$path_var . "<br>";
                    $id = $conn->query("SELECT * FROM books WHERE path LIKE '%$path_var%'");
                    if ($id) {
                        $id = $id->fetch_assoc()["id"];
                        //echo "BOOK ID =" . $id;
                        $tn_path = "ebook/tumbnails/" . $id . "." . mime2ext($img['mime']);
                        //echo("<br>BOOK TN_Path = " . $tn_path);
                        $conn->query("UPDATE books SET tn_path = '" . $tn_path . "' WHERE id = $id");

                        file_put_contents($tn_path, $img['data']);
                        optimizeImage($conn, $tn_path, $_SESSION["id"]);

                        linLog($conn, "[OK] Uploaded: " . $query_title, $_SESSION["id"]);
                        header("Location: library.php");
                    }
                }
            } else {
                linLog($conn,"[ERROR!] ". $conn->error, $_SESSION["id"]);
            }
        }
    }

}