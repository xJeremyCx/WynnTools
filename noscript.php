<?php
if(isset($_GET['p']))
{
    $use = true;
}
?>
<html>
    <body>
    <script type="text/javascript">
        <?php
        if($use)
        {
        echo 'location.replace("http://wynn.jeremycheong.com/player.php?name=' . $_GET['p'] . '");';
        }
        else
        {
        echo 'location.replace("http://wynn.jeremycheong.com");';
        }
        ?>
    </script>
    <noscript>
        <?php
        echo "Hello Wynncrafter! It seems like JavaScript in your browser has been disabled or your browser doesn't support JavaScript<br>";
        echo "Please enable JavaScript then refresh this page again<br>";
        echo "If your browser doesn't support JavaScript, try using other browsers like Chrome or Firefox";
        ?>
    </noscript>
    </body>
</html>