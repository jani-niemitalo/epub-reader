<?php
session_start();
include("session.php");
require_once('epub.php');
require_once("cover.php");
require_once("enumToInt.php");

$authorized = false;
if (enumToInt($_SESSION["perm_lvl"]) >= 2) {
    $authorized = true;
}
$book_id = mysqli_real_escape_string($conn, $_GET['id']);
$booksQuery = "SELECT * FROM books WHERE id=$book_id";
$booksQueryResult = $conn->query($booksQuery);
$db_book = $booksQueryResult->fetch_assoc();

try {
    $epub = new EPub($db_book["path"]);
} catch (Exception $e) {
    echo $e;
}


$bookPerm1 = "";
$bookPerm2 = "";
$bookPerm3 = "";
$bookPerm4 = "";
if (enumToInt($db_book["permission_lvl"]) == 0)
    $bookPerm1 = "selected";
if (enumToInt($db_book["permission_lvl"]) == 1)
    $bookPerm2 = "selected";
if (enumToInt($db_book["permission_lvl"]) == 2)
    $bookPerm3 = "selected";
if (enumToInt($db_book["permission_lvl"]) == 3)
    $bookPerm4 = "selected";


$Title = htmlspecialchars($db_book["title"]);
$ISBN = htmlspecialchars($db_book["isbn"]);
$series = htmlspecialchars($db_book["series"]);
$permLVL = "";


if ($authorized) {
    $Title = '<input 
                id="Title" 
                type="text" 
                onfocus="saveInitialValue(`Title`)"
                onfocusout="updateData(`Title`, ' . $db_book["id"] . ')"
                value="' . htmlspecialchars($db_book["title"]) . '">';
    $ISBN = '<input 
                id="ISBN" 
                type="text" 
                onfocus="saveInitialValue(`ISBN`)"
                onfocusout="updateData(`ISBN`, ' . $db_book["id"] . ')" 
                value="' . htmlspecialchars($db_book["isbn"]) . '">';
    $series = '<input 
                id="series" 
                type="text" 
                onfocus="saveInitialValue(`series`)" 
                onfocusout="updateData(`series`, ' . $db_book["id"] . ')"
                value="' . htmlspecialchars($db_book["series"]) . '">';
    $permLVL = '<div>
                  <h3 class="book_info_H3">Permission LVL </h3>
                  <select onchange="parse(        '.$db_book["id"].')" id="permSelector">
                      <option value="guest"       '.$bookPerm1.'>1</option>
                      <option value="user"        '.$bookPerm2.'>2</option>
                      <option value="uploader"    '.$bookPerm3.'>3</option>
                      <option value="admin"       '.$bookPerm4.'>4</option>
                  </select> 
                </div>';
}
if (htmlspecialchars($db_book["series"]) == "") {

    $series = '<div id="button" class="button" onclick="querySeries(' . $db_book["id"] . ')">Query series info</div>';

}


echo /*'
<span class="close" onclick="function f() {
  var modal2 = document.getElementById("myModal");
  modal2.style.display = "none";
}">&times;</span>*/
'<div class="bookViewWrapper">
' . coverFN2($db_book, "reader") . '
    <div class="book_info">
        <div class="book_info_ta">
            <h3 class="book_info_H3">Title</h3>
            ' . $Title . '
        </div>
        
        <div class="book_info_ta">
            <h3 class="book_info_H3">Genre </h3> ' . htmlspecialchars(join($epub->Subjects())) . '
        </div>
        
        <div class="book_info_ta">
            <h3 class="book_info_H3">Publisher </h3> ' . htmlspecialchars($epub->Publisher()) . '
        </div>
        
        <div class="book_info_ta">
            <h3 class="book_info_H3">ISBN </h3>
            ' . $ISBN . '
        </div>
        
        <div class="book_info_ta">
            <h3 class="book_info_H3">Series </h3>
            ' . $series . '
        </div>
        '.$permLVL.'        
    </div>
    <div class="description">
        '.$epub->Description() .'
    </div>
</div>';