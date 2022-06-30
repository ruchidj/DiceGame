<?php
header("Cache-Control: no cache");
session_cache_limiter("private_no_expire");

// Start the session
session_start();

// Sign-up function
function signup()
{
  // Set current logged in username
  $_SESSION["username"] = $_POST["username"];

  // Add newly logged in user to the database
  if (isset($_SESSION["user_database"])) {
    $_SESSION["user_database"] += [
      $_SESSION["username"] => $_POST["password"],
    ];
  } else {
    $_SESSION["user_database"] = [
      $_SESSION["username"] => $_POST["password"],
    ];
  }
  // Prevent permenant login
  unset($_POST);
  // Navigate to game board
  header("Location: ./state.php");
}

// Login function
function login()
{
  if (isset($_SESSION["user_database"])) {
    $local_flag = 0;
    // Set current logged in username
    $_SESSION["username"] = $_POST["username"];
    foreach ($_SESSION["user_database"] as $key => $value) {
      if ($key == $_SESSION["username"] && $value == $_POST["password"]) {
        $local_flag = 1;
        break;
      }
    }
    // If the username and password are prest and correct...
    if ($local_flag == 1) {
      unset($_POST);
      // Navigate to the game board
      header("Location: ./state.php");
      // Kill Script
    } else {
      // Username or password is incorrect
      echo "<h1>Username and password dont match, are you sure you signed up?</h1>";
    }
  } else {
    echo "<h1>You are not signed up!</h1>";
    return; // If there is no data base initialized, no one has signed up
  }
}
// Login listener
if (isset($_POST["login_button"])) {
  login();
}
// Signup listener
if (isset($_POST["signup"])) {
  signup();
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="index.css" />
    <title>Project 1</title>
  </head>
  <body>
    <div id="leaderboard">
      <h1 id="leader_title">Leaderboard</h1>
      <span id="large"><span id="small">User1</span> 20</span>
      <span id="large"><span id="small">User2</span> 23</span>
      <span id="large"><span id="small">User3</span> 27</span>
      
      <?php // * Change to leaderboard db for production!

if (isset($_SESSION["user_database"])) {
        foreach ($_SESSION["user_database"] as $key => $value) {
          echo "<span>$key </span>";
        }
      } ?>
    </div>


    <form id="inputs" method="post">
      <input type="text" placeholder="@username" name="username"  />
      <input type="text"  placeholder="password" name="password"  />
      <input type="submit" name="login_button" value="Login"></input>
      <input type="submit" name="signup" value="Sign-Up" />
    </form>

    </div>
  </body>
</html>
