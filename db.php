<?php
$conn = mysqli_connect(
"sql200.infinityfree.com",
"if0_39063003",
"pXWQRvchOq",
"if0_39063003_hms",
3306
);

if(!$conn){
    die("Connection Error: " . mysqli_connect_error());
}
?>
