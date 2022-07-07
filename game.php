<?php
header("Cache-Control: no cache");
session_start();

// Function to move the user and calulate needed variables:
function move($value){
    // Update the current tile:
    $_SESSION["currentValue"] = ($_SESSION["currentValue"] + $value); 
    // Update the total value(for leaderboard):
    $_SESSION["total_value"] = ($_SESSION["total_value"] + $value);
    // Output updated total_value:
    echo "
    <div id='header'>
    <h1>Roll & Run </h1>
    <img id=dice src=\"./images/$value.png\"<br>
    <span id='score'>Total score:</span> <br>";
    echo $_SESSION["total_value"];
    // If current tile space >= total tile spaces
    if($_SESSION["currentValue"]>=$_SESSION["total_moves"]){ 
        // If curr == total possible tiles, the player wins!
        if($_SESSION["currentValue"]==$_SESSION["total_moves"]){
            echo nl2br("\nYou won!\n");
            echo "<a href='./index.php'>Leaderboard</a>";
            $_SESSION["final_score"] = $_SESSION["total_value"]; // Save total score for leaderboard
            if(isset($_SESSION["leaderboard"])){
                // Post final_score to leaderboard
                 $_SESSION["leaderboard"][$_SESSION["username"]] = $_SESSION["final_score"];
            }
        }
        // Other wise they went over and therefore are back at the beginning of board(snake/chute/slide)
        else{
            $_SESSION["currentValue"] =  $_SESSION["currentValue"] % $_SESSION["total_moves"]  ;
        }
    }
    // Close the header / current score board
    echo "</div>";
}
// Listener with $GET so that re-submission warning doesnt show on refresh
if(isset($_GET['roll'])) {
    move(rand(1,6));
    unset($_GET);
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
    <div class="gameboard">
        <?php
        // Display the tiles so that they alternate color
        for($i= 0, $j=0; $i <= $_SESSION['total_moves']; $i++){
            if($i == $_SESSION['currentValue']){
                echo "<span class=cbox><h1>*</h1></span>";
            }
            else if($j == 0){
                echo "<span class=ybox></span>";
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
    }
    ?>
    </div>
    <br>
    <form method="get">
        <input type="submit" name="roll" 
        <?php if (($_SESSION["currentValue"]>=$_SESSION['total_moves'])){ ?>disabled <?php } ?> value="Roll the die"/>
    </form>
</body>
</html>
