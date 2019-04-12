<!DOCTYPE html>
<html>
<head>
<?php
require_once("DB/mysqlConnection.php");
$book_id = mysqli_real_escape_string($conn, $_GET['id']);
$booksQuery = "SELECT * FROM books WHERE id=$book_id";
$booksQueryResult = $conn->query($booksQuery);
$db_book = $booksQueryResult->fetch_assoc();

?>
<title><?php echo $db_book["title"];?></title>
<meta charset="utf-8"/>
<link rel="stylesheet" href="Helpers/styles.css">
</head>
<body>
    <?php
        require_once('Helpers/epub.php');
        require_once("Helpers/cover.php");
        $epub = new EPub($db_book["path"]);

        if ($db_book["tn_path"] == "")
        {
            $var = "lelkek";
        }
        else {
            $var = "keklel";
        }

    ?>
    <div class="bookViewWrapper">
        <?php echo coverFN($db_book, "reader.php?id=");?>
        <div class="book_info">
            <div class="book_info_ta">
            <h3 class="book_info_H3">Title</h3><?php echo htmlspecialchars($epub->Title())?>
            </div>
            <div class="book_info_ta">
            <h3 class="book_info_H3">Genre</h3><?php echo htmlspecialchars(join($epub->Subjects()))?>
            </div>
            <div class="book_info_ta">
            <h3 class="book_info_H3">Publisher</h3><?php echo htmlspecialchars($epub->Publisher())?>
            </div>
            <div class="book_info_ta">
            <h3 class="book_info_H3">ISBN</h3><?php echo htmlspecialchars($epub->ISBN())?>
            </div>
        </div>
        <div class="description">
            <?php echo $epub->Description()?>
        </div>


    </div>;
</body>
</html>
