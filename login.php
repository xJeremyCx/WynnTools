<?php
session_start();
if(!isset($_POST['name']))
{
    echo "N";
    exit(0);
}

$username = $_POST['name'];

if(@file_get_contents('http://api.wynncraft.com/public_api.php?action=playerStats&command=' . $username))
{
    $_SESSION["username"] = $username;
    echo "Y";
}
else
{
    echo "N";
}