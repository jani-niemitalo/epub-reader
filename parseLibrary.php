<?php
require_once('epub.php');
require_once("mysqlConnection.php");
require_once("mime2ext.php");

function getDirContents($dir, &$results = array()){
    $files = scandir($dir);

    foreach($files as $key => $value){
        $path = $dir.DIRECTORY_SEPARATOR.$value;
        if(!is_dir($path)) {
            $results[] = $path;
        } else if($value != "." && $value != "..") {
            getDirContents($path, $results);
            $results[] = $path;
        }
    }

    return $results;
}
$conn->query("DELETE FROM `books` WHERE 1");
$old = umask(0);


if( !is_dir("ebook/tumbnails") ) {
    mkdir(".ebook/tumbnails", 0755, true);
}
umask($old);


sleep(1);
foreach (getDirContents('ebook/books') as $v) {
    if (strripos($v, '.epub', -0)){
    $epub = new EPub($v);
    $var = $epub->ISBN();
    $img = $epub->Cover();
    $title = $epub->Title();
    $author = "";

        foreach ($epub->Authors() as $auth) {
            $author .= $auth. " ";
        }
    $series = "";
//if ($var == "") {
//        echo "Found empty ISBN, skipping: " . $v . "<br/>";
//        continue;
//    }
    if ($v == ""){
        echo "Found empty Path, skipping: " . $v . "<br/>";
        continue;
    }

    $sqlInsert = "REPLACE INTO books (isbn, path, title, author, series) VALUES ('" . mysqli_real_escape_string($conn,$var) . "', '" . mysqli_real_escape_string($conn,$v) . "', '" . mysqli_real_escape_string($conn,$title) . "', '" . mysqli_real_escape_string($conn,$author) . "', '" . mysqli_real_escape_string($conn,$series) . "')";
    echo $sqlInsert . "<br>";
    $sqlResult = $conn->query($sqlInsert);
    if ($sqlResult){
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
            }
        }
        //echo "[OK] " . $var. "<br/>";
    }
    else{
        //echo "[ERR] " . $var . "<br/>";
        echo $conn->error . "<br/>";
    }
}
}


?>
