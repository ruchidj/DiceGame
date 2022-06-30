<!--

This page is for TESTING PURPOSES.
It allows us to visually see the state moving from login, 
to whatever screen were to come next.
In our case, that will obviously be the gameboard.

-->

<?php
header("Cache-Control: no cache");
session_cache_limiter("private_no_expire");
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Current State</title>
</head>
<body>
  <h1>All Current Session Variables</h1>
  <hr />
 <?php print_r($_SESSION); ?>
 <h1>Current User </h1>
 <hr/>
 <?php
 echo "Username: ";
 echo $_SESSION["username"];
 echo nl2br("\n");
 ?> <h1>User Database</h1>
 <hr/>
 <?php foreach ($_SESSION["user_database"] as $key => $value) {
   echo nl2br("\n<span>$key -- > $value</span>\n");
 } ?>
 <h1>Leaderboard</h1>
 <hr />
 <?php if (isset($_SESSION["score_database"])) {
   foreach ($_SESSION["score_database"] as $key => $value) {
     echo nl2br("\n<span>$key -- > $value</span>\n");
   }
 } ?>
</body>
</html>