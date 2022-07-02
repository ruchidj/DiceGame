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
    <title>Snakes & Ladders </title>
  </head>

  <body>
    <main>

    <div id="leaderboard">
      <h1 id="leader_title">Leaderboard</h1>
      <table>
        <tr>
          <th>Username</th>
          <th>Best Score</th>
        </tr>
      <?php // * Change to leaderboard db for production!

if (isset($_SESSION["user_database"])) {
        foreach ($_SESSION["user_database"] as $key => $value) {
          echo "<tr><td>$key</td><td>$value</td></<tr>";
        }
      } else {
        echo "<span class='signup_warning'>If this is your first time playing with us, be sure to enter a username & password you can remember. Then press 'Sign-Up!'</span>";
      } ?>
      </table>
    </div>

    <form id="input_form" method="post" autocomplete="off">
      <input  id="username_input" type="text" placeholder="@username" name="username"  />
      <input id="password_input" type="text"  placeholder="Password" name="password"  />
      <input id="login" type="submit" name="login_button" value="Login"></input>
      <input id="signup" type="submit" name="signup" value="Sign-Up" />
    </form>

    </main>
  </body>
</html>
