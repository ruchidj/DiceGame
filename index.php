<?php
header("Cache-Control: no cache");
session_cache_limiter("private_no_expire");

// Start the session
session_start();
// Initialize gameboard / leaderboard variables for use in the game.php
$_SESSION["currentValue"]= 0;
$_SESSION["total_value"] = 0;
$_SESSION["total_moves"] = 10;
$_SESSION["current_level"] = 1;
$_SESSION["level_multiplier"] = [1, .5, .3];
$_SESSION["level_limits"] = [30, 10, 3];
//setcookie("VALIDATED","true",time()+3600);
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
  if(isset( $_SESSION['leaderboard'])){
    $_SESSION['leaderboard'] += [$_POST["username"]=>1000];
  }
  else{
    $_SESSION['leaderboard'] = [$_POST["username"]=>1000];
  }
  // Prevent permenant login
  unset($_POST);
  // Navigate to game board
  header("Location: ./game.php");
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
      header("Location: ./game.php");
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
      if (isset($_SESSION["leaderboard"])) {
        foreach ($_SESSION["leaderboard"] as $key => $value) {
          echo "<tr><td>$key</td><td>$value</td></<tr>";
        }
      } else {
        echo "<span class='signup_warning'>If this is your first time playing with us, be sure to enter a username & password you can remember. Then press 'Sign-Up!'</span>
        <br> The point of the game is to get as few points as possible. <br>
        Each move you make in level one is 1 point.<br>
        In level two each move is half of a point. <br>
        Level three, the hardest level to get to, each move is only a 1/3 of a point!";
      } ?>
      </table>
    </div>

    <form id="input_form" method="post" autocomplete="off">
      <input  id="username_input" type="text" placeholder="@username" name="username"  />
      <input id="password_input" type="text"  placeholder="Password" name="password"  />
      <input id="login" type="submit" name="login_button" value="Login"></input>
      <input id="signup" type="submit" name="signup" value="Sign-Up" />
    </form>
    <a href="summary.html"> About Us </a>
    </main>
  </body>
</html>
