<?php
require_once("cover.php");
require_once("enumToInt.php");
session_start();
require_once("session.php");
require_once("upload_parser.php");

$userID = "";
$userID .= (string)mysqli_real_escape_string($conn, $_SESSION["id"]);
$target_dir = "uploads/" . $userID . "/";
echo "<script>var global_target = \"" . $target_dir . "\";</script>";
$target_file_list = array();
$uploadOk = 1;
try {
    if (!is_dir($target_dir))
        mkdir($target_dir, 0755, true);
    else {
        include("upload_cleanup.php");
        cleanUp($target_dir);
    }
} catch (Exception $e) {
    echo $e;
    exit($e);
}


function reArrayFiles(&$file_post)
{


    $file_ary = array();
    $file_count = count($file_post['name']);
    $file_keys = array_keys($file_post);

    for ($i = 0; $i < $file_count; $i++) {
        foreach ($file_keys as $key) {
            $file_ary[$i][$key] = $file_post[$key][$i];
        }
    }

    return $file_ary;
}
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
function commit()
{

}

?>


<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<script type="text/javascript">

</script>


    <?php
    if ($_FILES['upload']) {
        try {
            $file_ary = reArrayFiles($_FILES['upload']);
        } catch (Exception $e) {
            echo "<script>console.log('" . $e . "')</script>";
            exit($e);
        }

        echo'
<form method="post" action="upload_commit.php">
    <input type="submit" value="Commit changes" name="commit">
</form>';
        echo "<div class=\"up_grid\">";
        foreach ($file_ary as $file) {
            $uploadOk = 1;
            $error_msg = "";

            $target_file = $target_dir . $file['name'];
            //echo $file["tmp_name"];

            if (file_exists($target_file)) {
                $error_msg = "Sorry, file already exists.";
                $uploadOk = 0;
            }
            $ext = pathinfo($file["name"], PATHINFO_EXTENSION);
            if ($ext != "epub") {
                $error_msg = "Invalid file type \"." . $ext . "\", only \".epub\" allowed.";
                $uploadOk = 0;
            }

            if ($uploadOk == 0) {
                echo "<script>console.log('Sorry, your file was not uploaded. ERROR: \'" . $error_msg . "\'');</script>";
            } else {

                if (move_uploaded_file($file['tmp_name'], $target_file)) {
                    echo "<script>console.log('The file " . basename($file["name"]) . " has been uploaded.');</script>";
                    echo parseFile($target_file, $target_dir);
                } else {
                    echo "<script>console.log('Sorry, there was an error uploading your file.');</script>";
                }
            }

        }
        echo "</div>";
    } else
        echo "
<form action=\"upload.php\" method=\"post\" enctype=\"multipart/form-data\">
    Select image to upload:
    <input name=\"upload[]\" type=\"file\" multiple>
    <input type=\"submit\" value=\"Upload file\" name=\"submit\">
</form>";
    ?>

<script type="text/javascript">

</script>
</body>
</html>

