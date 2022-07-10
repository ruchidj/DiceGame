<?php
session_start();
if(!isset($_COOKIE["VALIDATED"])){
    // If this cookie is not set, they didnt log in & likely
    // accessed this page via direct URL path
    header("location:./index.php"); // Send to login!
}

// Function to move the user and calulate needed variables:
function move_user($dice_value, $current_level, $level_multiplier){
    // Update the current tile for UI:
    $_SESSION["current_value"] = ($_SESSION["current_value"] + $dice_value); 
    // Update total score:
    $_SESSION["total_value"] = ($_SESSION["total_value"] + ($dice_value * $level_multiplier));
    // Output updated total_value:
    
    // If current tile space >= total tile spaces
    if($_SESSION["current_value"]>=$_SESSION["total_moves"]){ 
        // If curr == total possible tiles
        // && the their score is <= limit to go to next level 
        // && the player is on level 2 out of 3....
        if($_SESSION["current_value"]==$_SESSION["total_moves"] &&
         $_SESSION["total_value"] <= $_SESSION["level_limits"][$current_level-1] &&
         $_SESSION["current_level"] <= 2
         ){
            $_SESSION["current_value"] = 0; // Reset position to begnning of leaderboard
            $_SESSION["total_value"] = 0; // Reset the players total score
            $_SESSION["current_level"] += 1; // Move to next level
        }
         // If above wasnt met, but they're still at the end of tiles -> Game over!
        elseif ($_SESSION["current_value"]==$_SESSION["total_moves"]) {
            echo "<div id='completed_game'>";
            echo "<h3>Great Work!</h3>";
            echo "<a href='./index.php'>Leaderboard</a><br></div>";

              // Post final_score to leaderboard
            if(isset($_SESSION["leaderboard"])){
                // Check that the score is actually better (lower) than the one they previously had!
                if($_SESSION["leaderboard"][$_SESSION["username"]] >= $_SESSION["total_value"]){
                    $_SESSION["leaderboard"][$_SESSION["username"]] = $_SESSION["total_value"];
                }  
                // Prevent the user from re-entering the game via "back" button
                setcookie("VALIDATED", "", time() - 1800, "/", "", 0);
            }
        }     
        else{ // Other wise they went over and therefore are back at the beginning of board(snake/chute/slide)
            $_SESSION["current_value"] = $_SESSION["current_value"] % $_SESSION["total_moves"];
        }
    }
    echo "
    <div id='header'>
    <h1>Roll & Run </h1>
    <span >Level $current_level</span><br>
    <img id=dice src=\"./images/$dice_value.png\"><br>
    <span id='score'>Total score:</span>";
    echo $_SESSION["total_value"];
    echo nl2br("\n\nScore Limit: ");
    echo $_SESSION["level_limits"][$current_level-1]; 
    if ($_SESSION["current_value"]!= 0 ){
        echo nl2br("\n\nCurrent tile:\n");
        if ($_SESSION["current_value"] > $_SESSION["total_moves"]){
            echo $_SESSION["current_value"] % $_SESSION["total_moves"];
        }
        else{
            echo $_SESSION["current_value"];
        }    
    }
    // Close the header / current score board
    echo "</div>";
}
// Listener with $GET so that re-submission warning doesnt show on refresh
if(isset($_GET['roll'])) {
    $curr_level = $_SESSION["current_level"];
    // The current level starts at 1 so we have to account for the offset:
    move_user(rand(1,6), $curr_level, $_SESSION["level_multiplier"][$curr_level-1]); 
    unset($_GET);
}
?>

<!-- HTML Output: -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="game.css" />
  <title>Roll & Run</title>
</head>
<body>
    <?php
    $background_changer = $_SESSION["current_level"];
    echo "<div class=\"gameboard$background_changer\">";
    // Display the tiles so that they alternate color
    // and the current tile is different than all of the rest.
    $start_flag = true;
    for($i= 0, $j=0; $i <= $_SESSION['total_moves']; $i++){
        if($i == $_SESSION['current_value']){
            echo "<span class=cbox><h1>*</h1></span>";
        }
        else if($j == 0){
            if($start_flag && $j != $_SESSION['current_value'] ){
                echo "<span class=ybox>Start</span>";
                $start_flag = false;
            }
            else{
                echo "<span class=ybox></span>";
            }
            $j++;
        }
        else if( $j== 1){
            echo "<span class=gbox></span>";
            $j++;
        }
        else if($j == 2){
            echo "<span class=rbox></span>";
            $j = 0;
        }
    }
    ?>
    </div>
    <br>
    <form method="get">
        <input type="submit" name="roll" 
        <?php if (($_SESSION["current_value"]>=$_SESSION['total_moves'])){ ?>disabled <?php } ?> value="Roll the die"/>
    </form>
</body>
</html>
