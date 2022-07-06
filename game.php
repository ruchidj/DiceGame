<?php
header("Cache-Control: no cache");
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="game.css" />
  <title>Dice Roll Game</title>
</head>
<body>
<center><h1> Dice Roll Game </h1>
  <?php
 echo "Username: ";
 echo $_SESSION["username"];
 echo nl2br("\n");

$_SESSION["currentValue"];
$_SESSION["End"] = 0;
$count = array_fill(0,11,0);

$count[1] = 4;
$count[5] = 2;
$count[6] = 8;
$count[7] = 3;

if(isset($_POST['Rolldie'])) {
    rollthedie();
}

function rollthedie()
{
    $die = rand(1,6);
    echo "<img id=dice src=".$die . ".png>";
    $steps = $die;
    move($steps);

}

function move($die){
    global $count;
    echo "<div class=gameboard>";
    $_SESSION["currentValue"] = ($_SESSION["currentValue"] + $die);
    if($_SESSION["currentValue"] <10 ){
        if($count[$_SESSION["currentValue"]]){
            $_SESSION["currentValue"] = $count[$_SESSION["currentValue"]];
        }  
    }
    if($_SESSION["currentValue"]>=10){
        $_SESSION["currentValue"] = 10;
        echo "you won";
    }
    for($i= 1 ; $i< count($count) ; $i++){    
        if($i == $_SESSION["currentValue"]){
            echo "<div class=cbox></div>";
        }else if($count[$i] == 0){
            echo "<div class=ybox></div>";
        } 
        else if($count[$i]>$i){
            echo "<div class=gbox></div>";

        }else if($count[$i]<$i && $count[$i]!=0){
            echo "<div class=rbox></div>";
        }
         
    }
    if($_SESSION["currentValue"]==10){
        gameend();
    }

}

echo "</div>" ;

function gameend(){
    echo "End of game";
    $_SESSION["currentValue"]=0;
    $_SESSION["End"]=11;
}

?>

  <form method="post">
        <input type="submit" name="Rolldie" <?php if ($_SESSION["End"] == '11'){ ?>disabled <?php } ?> value="Roll the die"/>
    </form>
</center>


</body>

</html>
