<?php
$conn = mysqli_connect(
"URL",
"DB  URL",
"PASSWORD",
"DB NAME",
PORT NO
);

if(!$conn){
    die("Connection Error: " . mysqli_connect_error());
}
?>
