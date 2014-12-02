<?php
if(!isset($_POST['cmd']))
{
    exit();
}
$cmd = $_POST['cmd'];
if($cmd=="online")
{
    echo json_decode(file_get_contents("http://api.wynncraft.com/public_api.php?action=onlinePlayersSum"), true)['players_online'];
    exit();
}
if($cmd=="isOnline")
{
    if(!isset($_POST['p']))
    {
        exit();
    }

    if(!@file_get_contents("http://api.wynncraft.com/public_api.php?action=playerStats&command=" . $_POST['p']))
    {
        echo "N|Player not found!";
        exit();
    }

    $server = json_decode(file_get_contents("http://api.wynncraft.com/public_api.php?action=playerStats&command=" . $_POST['p']), true)['current_server'];
    if($server=="null")
    {
        echo "Y|" . $_POST['p'] ." is not online.";
    }
    else
    {
        echo "Y|" . $_POST['p'] . " is online at " . $server;
        exit(0);
    }
}
if($cmd=="find")
{
    if(!isset($_POST['p']))
    {
        exit();
    }

    if(!@file_get_contents("http://api.wynncraft.com/public_api.php?action=playerStats&command=" . $_POST['p']))
    {
        echo "N|Player not found!";
        exit();
    }

    echo 'Y|http://wynn.jeremycheong.com/player.php?name=' . $_POST['p'];
    exit();
}