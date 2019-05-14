<?php
session_start();
include("session.php");
require_once('epub.php');

$book_id = mysqli_real_escape_string($conn, $_GET['id']);
if ($book_id == "")
    exit("No input");




    $booksQuery1 = "SELECT series_i FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = library AND TABLE_NAME = books";
    $booksQueryResult1 = $conn->query($booksQuery1);
    echo($booksQueryResult1);

    if ($booksQueryResult1 == "") {
        $booksQuery1 = "ALTER TABLE books add COLUMN `series_i` int(3)";
        $booksQueryResult1 = $conn->query($booksQuery1);
    }
//echo($booksQueryResult1);
//exit();

    $booksQuery2 = "SELECT * FROM books WHERE id=$book_id";
    $booksQueryResult2 = $conn->query($booksQuery2);
    $db_book = $booksQueryResult2->fetch_assoc();

    $isbn = $db_book["isbn"];
    if ($isbn == "")
        exit("TODO: Implement search with no ISBN;");
//if ($db_book["series"] != "") {
    //exit("Allready has series, no need to bother API again");
//}

//echo $isbn. "<br>";
//echo $db_book["title"]. "<br>";
    $data = array('key' => 'FJ5aUJlUc4pBadYJmcbaA', 'isbn' => $isbn);
    $goodreads_bookid = CallAPI('GET', "https://www.goodreads.com/book/isbn_to_id", $data);
    if ($goodreads_bookid == "")
        exit("No goodreads_bookid found");
//echo $goodreads_bookid. "<br>";

    $data = array('key' => 'FJ5aUJlUc4pBadYJmcbaA', 'id' => $goodreads_bookid);
    sleep(1);
    $goodreads_result = CallAPI('GET', "https://www.goodreads.com/book/id_to_work_id", $data);
    if ($goodreads_result == "")
        exit("No goodreads_workid found");
    $xml = simplexml_load_string($goodreads_result) or die("Error: Cannot create object");
    $goodreads_workid = $xml->{'work-ids'}->item;
//echo $goodreads_workid. "<br>";

    $data = array('key' => 'FJ5aUJlUc4pBadYJmcbaA');
    sleep(1);
    $goodreads_result2 = CallAPI('GET', "https://www.goodreads.com/series/work/$goodreads_workid?format=xml", $data);
    if ($goodreads_result2 == "")
        exit("No goodreads_series found");
    $xml = simplexml_load_string($goodreads_result2) or die("Error: Cannot create object");

    $goodreads_series_number = $xml->{'series_works'}->{'series_work'}->user_position;
    $goodreads_series_number = (int)trim($goodreads_series_number);
    $goodreads_series_title = $xml->{'series_works'}->{'series_work'}->series->title;
    $goodreads_series_title = trim($goodreads_series_title);
    $query = "UPDATE books SET series = '".$goodreads_series_title."' WHERE id = '".$book_id."'";
    $response = $conn->query($query);

    $query2 = "UPDATE books SET series_i = '".$goodreads_series_number."' WHERE id = '".$book_id."'";
    $response2 = $conn->query($query2);
    if ($response){
        echo("[OK1]". $query);
    }
    else{
        echo( "[ERR1]". $conn->error);
    }

    if ($response2){
        exit("[OK2]". $query2);
    }
    else{
        exit( "[ERR2]". $conn->error);

}



function CallAPI($method, $url, $data = false)
{


// use key 'http' even if you send the request to https://...
    $options = array(
        'http' => array(
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => $method,
            'content' => $data != false ? http_build_query($data) : "",
        ),
    );
    $context  = stream_context_create($options);
    $result = file_get_contents($url, false, $context);

    return $result;
}
?>