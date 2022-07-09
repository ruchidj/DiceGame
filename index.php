<?php
// Start the session
session_start();
// Initialize gameboard / leaderboard variables for use in the game.php
$_SESSION["current_value"]= 0;
$_SESSION["total_value"] = 0;
$_SESSION["total_moves"] = 10; // 10 Total moves per level
$_SESSION["current_level"] = 1; // Starting level of 3
$_SESSION["level_multiplier"] = [1, .5, .3]; // Roll : Point ratio per level
$_SESSION["level_limits"] = [30, 10, 3]; // Limit of points per level to advance

// Sign-up function
function signup()
{
  // Set current logged in username
  $_SESSION["username"] = $_POST["username"];
  // Add newly signedup/logged in user to the database
  if (isset($_SESSION["user_database"])) {
    $_SESSION["user_database"] += [
      $_SESSION["username"] => $_POST["password"],
    ];
  } else {
    $_SESSION["user_database"] = [
      $_SESSION["username"] => $_POST["password"],
    ];
  }
  // Add newly signedup/logged in user to the leaderboard
  if(isset( $_SESSION['leaderboard'])){
    $_SESSION['leaderboard'] += [$_POST["username"]=>1000];
  }
  else{
    $_SESSION['leaderboard'] = [$_POST["username"]=>1000];
  }
  // Prevent permenant login
  unset($_POST);
  // Set cookie 30 minutes
  setcookie("VALIDATED","true",time()+1800); 
  // Navigate to game board
  header("Location: ./game.php");
  exit();
}

// Login function
function login()
{
  if (isset($_SESSION["user_database"])) {
    $user_exists = false;
    // Set current logged in username
    $_SESSION["username"] = $_POST["username"];
    foreach ($_SESSION["user_database"] as $key => $value) {
      // Ignore case for password!
      if ($key == $_SESSION["username"] && strtolower($value) == strtolower($_POST["password"])) {
        $user_exists = true;
        break;
      }
    }
    // If the username and password are present & correct...
    if ($user_exists) {
      unset($_POST);
      setcookie("VALIDATED","true",time()+1800); // Set cookie 30 minutes
      header("Location: ./game.php"); // Navigate to the game board
      exit();
    } else { // Username or password is incorrect (show warning)
      echo "<h3 id=\"warning\">Username and password dont match, are you sure you signed up?</h3>";
      echo "<a href=\"./index.php\">Click Here to try again</a>";
      exit();
    }
  } else { // Not one user has signed up (show warning)
    echo "<h1>You are not signed up!</h1>";
    echo "<a href=\"./index.php\">Click Here to try again</a>";
    exit(); 
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="index.css" />
    <title>Roll & Run</title>
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
      <?php 
      if (isset($_SESSION["leaderboard"])) {
        $sorted = [];
        foreach($_SESSION["leaderboard"] as $key => $value){
          $sorted += [$key => $value];
          asort($sorted);
        }
        foreach ($sorted as $key => $value) {
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
      <input id="password_input" type="password"  placeholder="Password" name="password"  />
      <input id="login" type="submit" name="login_button" value="Login"></input>
      <input id="signup" type="submit" name="signup" value="Sign-Up" />
    </form>
    <a id="summary" href="summary.html"> How to play </a>
    </main>
  </body>
</html>
