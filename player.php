<?php
if(!isset($_GET['name']))
{
    header("Location: http://wynn.jeremycheong.com");
    exit();
}
$username = $_GET['name'];
if(!@file_get_contents('http://api.wynncraft.com/public_api.php?action=playerStats&command=' . $username))
{
    header("Location: http://wynn.jeremycheong.com");
    exit();
}
$playerData = json_decode(file_get_contents('http://api.wynncraft.com/public_api.php?action=playerStats&command=' . $username), true);
$isOnline = true;
if($playerData['current_server']=="null")
{
    $isOnline = false;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <noscript>
        <meta http-equiv="refresh" content="0; url=http://wynn.jeremycheong.com/noscript.php?p=<?php echo $username ?>">
    </noscript>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo "WynnTools | " . $username;?></title>
    <link rel="icon" type="image/png" href="favicon.png">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-theme.min.css" rel="stylesheet">
</head>
<body>
<nav id="navBar" class="navbar navbar-default navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <a class="navbar-brand">WynnTools</a>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="http://wynn.jeremycheong.com">Home</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container-full">
<div class="backDefault<?php echo rand(1, 4); ?>">
<br>
<br>
<br>
<iframe style="display:block;margin:auto" src="http://api.wynncraft.com/skinviewer/<?php echo $username; ?>" title="skin" width="320" height="400" frameBorder="0"></iframe>
<br>
<div id="panel">
<div class="row margin-panel">
    <div class="panel-<?php if($isOnline) {echo "success";} else {echo "info";}?> text-center col-md-12">
        <div class="panel-heading"><h3 class="panel-title"><?php if($isOnline) {echo "Basic Information | Online : " . $playerData['current_server'];} else {echo "Basic Information | Offline or In Lobby";} ?></h3> </div>
        <div class="panel-body opaque">
            <h1 class="text-center"><strong><?php echo $username; ?></strong></h1>
            <?php
            switch($playerData['rank'])
            {
                case 'VIP':
                    if($playerData['tag']==null)
                    {
                        echo "<p style='color: #27F42D'>[Wynncraft VIP]</p>";
                    }
                    else
                    {
                        echo "<p style='color: #27F42D'>[Wynncraft VIP + Veteran]</p>";
                    }
                    break;
                case 'Moderator':
                    if($playerData['tag']==null)
                    {
                        echo "<p style='color: orange'>[Wynncraft Moderator]</p>";
                    }
                    else
                    {
                        echo "<p style='color: orange'>[Wynncraft Moderator + Veteran]</p>";
                    }
                    break;
                case 'Administrator':
                    if($playerData['tag']==null)
                    {
                        echo "<p style='color: darkred'>[Wynncraft Administrator]</p>";
                    }
                    else
                    {
                        echo "<p style='color: darkred'>[Wynncraft Administrator + Veteran]</p>";
                    }
                    break;
                case 'Media':
                    if($playerData['tag']==null)
                    {
                        echo "<p style='color: #CA4DD5'>[Wynncraft Media]</p>";
                    }
                    else
                    {
                        echo "<p style='color: #CA4DD5'>[Wynncraft Media + Veteran]</p>";
                    }
                    break;
                default:
                    echo "<p>[Wynncraft " . $playerData['rank'] . "]</p>";
                    break;
            }
            echo "<br>";
            echo "<h4>Total Playtime : <strong>" . $playerData['playtime'] . "</strong></h4>";
            echo "<h4>First Login : <strong>" . $playerData['first_join'] . "</strong></h4>";
            echo "<h4>First Last : <strong>" . $playerData['last_join'] . "</strong></h4>";
            ?>
        </div>
    </div>
</div>

<?php
$online = json_decode(file_get_contents("http://api.wynncraft.com/public_api.php?action=onlinePlayers"), true);
$friends = $playerData['friends'];
$hasfriends = true;
if(sizeof($friends)>1)
{
    $friendsno = sizeof($friends);
}
else
{
    $hasfriends = false;
    $friendsno = 0;
}

?>
<div class="row margin-panel">
    <div class="panel-primary col-md-6">
        <div class="panel-heading"><h3 class="panel-title text-center">Friends (<?php echo $friendsno . ")"?></h3></div>
        <div class="panel-body opaque panel-height">
            <?php

            function getArrayIndex($array, $searchString)
            {
                if(count($array))
                {
                    foreach(array_keys($array) as $key)
                    {
                        if(is_array($array[$key]))
                        {
                            foreach($array[$key] as $item)
                            {
                                if($item === $searchString)
                                {
                                    return $key;
                                }
                            }
                        }
                    }
                }
                return "";
            }

            if(!$hasfriends)
            {
                echo "<h4>" . $username . " doesn't have any friend.</h4>";
            }
            else
            {
                echo '<div class="list-group list-scroll">';

                foreach($friends as $friend)
                {
                    if($friend!="")
                    {
                        $result = getArrayIndex($online, $friend);
                        if($result!="")
                        {
                            echo '<a href="http://wynn.jeremycheong.com/player.php?name=' . $friend . '" class="list-group-item list-group-item-success"><span><img src="http://api.jeremycheong.com/minecraft.php?avatar=' . $friend . '&size=2" alt="img"></span>  ' . $friend . '<span class="badge">' . $result .'</span></a>';
                        }
                        else
                        {
                            echo '<a href="http://wynn.jeremycheong.com/player.php?name=' . $friend . '" class="list-group-item"><span><img src="http://api.jeremycheong.com/minecraft.php?avatar=' . $friend . '&size=2" alt="img"></span>  ' . $friend . '</a>';
                        }
                    }
                }
                echo "</div>";
            }
            ?>
        </div>
    </div>

    <div class="panel-primary col-md-6 text-center">
        <div class="panel-heading"><h3 class="panel-title">Global Statistics</h3></div>
        <div class="panel-body opaque panel-height">
            <?php
            $global = $playerData['global'];
            echo "<h4>Items Identified : <strong>" . $global['items_identified'] . "</strong></h4>";
            echo "<h4>Mobs Killed : <strong>" . $global['mobs_killed'] . "</strong></h4>";
            echo "<h4>Nether Kills : <strong>" . $global['pvp_kills'] . "</strong></h4>";
            echo "<h4>Nether Deaths : <strong>" . $global['pvp_deaths'] . "</strong></h4>";
            $kd = (float)$global['pvp_kills'] / (float)$global['pvp_deaths'];
            if($global['pvp_kills']!=0 && $global['pvp_deaths']!=0)
            {
                $kd = (float)$global['pvp_kills'] / (float)$global['pvp_deaths'];
                echo "<h4>Kills/Deaths Ratio : <strong>" . number_format($kd, 2) . "</strong></h4>";
            }
            else
            {
                echo "<h4>Kills/Deaths Ratio : <strong>0</strong></h4>";
            }
            echo "<h4>Chests Found : <strong>" . $global['chests_found'] . "</strong></h4>";
            echo "<h4>Blocks Walked : <strong>" . $global['blocks_walked'] . "</strong></h4>";
            echo "<h4>Total Logins : <strong>" . $global['logins'] . "</strong></h4>";
            echo "<h4>Total Deaths : <strong>" . $global['deaths'] . "</strong></h4>";
            echo "<h4>Total Levels : <strong>" . $global['total_level'] . "</strong></h4>";
            ?>
        </div>
    </div>
</div>

<div class="row margin-panel">
    <div class="panel-default col-md-6 text-center">
        <div class="panel-heading"><h3 class="panel-title">Warrior Statistics</h3></div>
        <div class="panel-body opaque panel-height">
            <div class="list-scroll">
                <?php
                $warrior = $playerData['classes']['warrior'];
                $warriord = $playerData['classes']['warrior']['dungeons_completed'];
                echo "<h4>Items Identified : <strong>" . $warrior['items_identified'] . "</strong></h4>";
                echo "<h4>Mobs Killed : <strong>" . $warrior['mobs_killed'] . "</strong></h4>";
                echo "<h4>Nether Kills : <strong>" . $warrior['pvp_kills'] . "</strong></h4>";
                echo "<h4>Nether Deaths : <strong>" . $warrior['pvp_deaths'] . "</strong></h4>";
                if($warrior['pvp_kills']!=0 && $warrior['pvp_deaths']!=0)
                {
                    $kd = (float)$warrior['pvp_kills'] / (float)$warrior['pvp_deaths'];
                    echo "<h4>Kills/Deaths Ratio : <strong>" . number_format($kd, 2) . "</strong></h4>";
                }
                else
                {
                    echo "<h4>Kills/Deaths Ratio : <strong>0</strong></h4>";
                }
                echo "<h4>Chests Found : <strong>" . $warrior['chests_found'] . "</strong></h4>";
                echo "<h4>Blocks Walked : <strong>" . $warrior['blocks_walked'] . "</strong></h4>";
                echo "<h4>Logins : <strong>" . $warrior['logins'] . "</strong></h4>";
                echo "<h4>Deaths : <strong>" . $warrior['deaths'] . "</strong></h4>";
                echo "<h4>Level : <strong>" . $warrior['level'] . "</strong></h4>";
                if(!empty($warriord))
                {
                    echo "<h4><strong>=-= Dungeons Completed =-=</strong></h4>";
                    if(array_key_exists("Zombie", $warriord))
                    {
                        echo "<h4>Zombie Dungeon : <strong>" . $warriord['Zombie'] . "</strong></h4>";
                    }
                    if(array_key_exists("Animal", $warriord))
                    {
                        echo "<h4>Animal Dungeon : <strong>" . $warriord['Animal'] . "</strong></h4>";
                    }
                    if(array_key_exists("Skeleton", $warriord))
                    {
                        echo "<h4>Skeleton Dungeon : <strong>" . $warriord['Skeleton'] . "</strong></h4>";
                    }
                    if(array_key_exists("Silverfish", $warriord))
                    {
                        echo "<h4>Silverfish Dungeon : <strong>" . $warriord['Silverfish'] . "</strong></h4>";
                    }
                }
                ?>
            </div>
        </div>
    </div>

    <div class="panel-default col-md-6 text-center">
        <div class="panel-heading"><h3 class="panel-title">Archer Statistics</h3></div>
        <div class="panel-body opaque panel-height">
            <div class="list-scroll">
                <?php
                $archer = $playerData['classes']['archer'];
                $archerd = $playerData['classes']['archer']['dungeons_completed'];
                echo "<h4>Items Identified : <strong>" . $archer['items_identified'] . "</strong></h4>";
                echo "<h4>Mobs Killed : <strong>" . $archer['mobs_killed'] . "</strong></h4>";
                echo "<h4>Nether Kills : <strong>" . $archer['pvp_kills'] . "</strong></h4>";
                echo "<h4>Nether Deaths : <strong>" . $archer['pvp_deaths'] . "</strong></h4>";
                if($archer['pvp_kills']!=0 && $archer['pvp_deaths']!=0)
                {
                    $kd = (float)$archer['pvp_kills'] / (float)$archer['pvp_deaths'];
                    echo "<h4>Kills/Deaths Ratio : <strong>" . number_format($kd, 2) . "</strong></h4>";
                }
                else
                {
                    echo "<h4>Kills/Deaths Ratio : <strong>0</strong></h4>";
                }
                echo "<h4>Chests Found : <strong>" . $archer['chests_found'] . "</strong></h4>";
                echo "<h4>Blocks Walked : <strong>" . $archer['blocks_walked'] . "</strong></h4>";
                echo "<h4>Logins : <strong>" . $archer['logins'] . "</strong></h4>";
                echo "<h4>Deaths : <strong>" . $archer['deaths'] . "</strong></h4>";
                echo "<h4>Level : <strong>" . $archer['level'] . "</strong></h4>";
                if(!empty($archerd))
                {
                    echo "<h4><strong>=-= Dungeons Completed =-=</strong></h4>";
                    if(array_key_exists("Zombie", $archerd))
                    {
                        echo "<h4>Zombie Dungeon : <strong>" . $archerd['Zombie'] . "</strong></h4>";
                    }
                    if(array_key_exists("Animal", $archerd))
                    {
                        echo "<h4>Animal Dungeon : <strong>" . $archerd['Animal'] . "</strong></h4>";
                    }
                    if(array_key_exists("Skeleton", $archerd))
                    {
                        echo "<h4>Skeleton Dungeon : <strong>" . $archerd['Skeleton'] . "</strong></h4>";
                    }
                    if(array_key_exists("Silverfish", $archerd))
                    {
                        echo "<h4>Silverfish Dungeon : <strong>" . $archerd['Silverfish'] . "</strong></h4>";
                    }
                }
                ?>
            </div>
        </div>
    </div>
</div>

<div class="row margin-panel">
    <div class="panel-default col-md-6 text-center">
        <div class="panel-heading"><h3 class="panel-title">Mage Statistics</h3></div>
        <div class="panel-body opaque panel-height">
            <div class="list-scroll">
                <?php
                $mage = $playerData['classes']['mage'];
                $maged = $playerData['classes']['mage']['dungeons_completed'];
                echo "<h4>Items Identified : <strong>" . $mage['items_identified'] . "</strong></h4>";
                echo "<h4>Mobs Killed : <strong>" . $mage['mobs_killed'] . "</strong></h4>";
                echo "<h4>Nether Kills : <strong>" . $mage['pvp_kills'] . "</strong></h4>";
                echo "<h4>Nether Deaths : <strong>" . $mage['pvp_deaths'] . "</strong></h4>";
                if($mage['pvp_kills']!=0 && $mage['pvp_deaths']!=0)
                {
                    $kd = (float)$mage['pvp_kills'] / (float)$mage['pvp_deaths'];
                    echo "<h4>Kills/Deaths Ratio : <strong>" . number_format($kd, 2) . "</strong></h4>";
                }
                else
                {
                    echo "<h4>Kills/Deaths Ratio : <strong>0</strong></h4>";
                }
                echo "<h4>Chests Found : <strong>" . $mage['chests_found'] . "</strong></h4>";
                echo "<h4>Blocks Walked : <strong>" . $mage['blocks_walked'] . "</strong></h4>";
                echo "<h4>Logins : <strong>" . $mage['logins'] . "</strong></h4>";
                echo "<h4>Deaths : <strong>" . $mage['deaths'] . "</strong></h4>";
                echo "<h4>Level : <strong>" . $mage['level'] . "</strong></h4>";
                if(!empty($maged))
                {
                    echo "<h4><strong>=-= Dungeons Completed =-=</strong></h4>";
                    if(array_key_exists("Zombie", $maged))
                    {
                        echo "<h4>Zombie Dungeon : <strong>" . $maged['Zombie'] . "</strong></h4>";
                    }
                    if(array_key_exists("Animal", $maged))
                    {
                        echo "<h4>Animal Dungeon : <strong>" . $maged['Animal'] . "</strong></h4>";
                    }
                    if(array_key_exists("Skeleton", $maged))
                    {
                        echo "<h4>Skeleton Dungeon : <strong>" . $maged['Skeleton'] . "</strong></h4>";
                    }
                    if(array_key_exists("Silverfish", $maged))
                    {
                        echo "<h4>Silverfish Dungeon : <strong>" . $maged['Silverfish'] . "</strong></h4>";
                    }
                }
                ?>
            </div>
        </div>
    </div>

    <div class="panel-default col-md-6 text-center">
        <div class="panel-heading"><h3 class="panel-title">Assassin Statistics</h3></div>
        <div class="panel-body opaque panel-height">
            <div class="list-scroll">
                <?php
                $assassin = $playerData['classes']['assassin'];
                $assassind = $playerData['classes']['assassin']['dungeons_completed'];
                echo "<h4>Items Identified : <strong>" . $assassin['items_identified'] . "</strong></h4>";
                echo "<h4>Mobs Killed : <strong>" . $assassin['mobs_killed'] . "</strong></h4>";
                echo "<h4>Nether Kills : <strong>" . $assassin['pvp_kills'] . "</strong></h4>";
                echo "<h4>Nether Deaths : <strong>" . $assassin['pvp_deaths'] . "</strong></h4>";
                if($assassin['pvp_kills']!=0 && $assassin['pvp_deaths']!=0)
                {
                    $kd = (float)$assassin['pvp_kills'] / (float)$assassin['pvp_deaths'];
                    echo "<h4>Kills/Deaths Ratio : <strong>" . number_format($kd, 2) . "</strong></h4>";
                }
                else
                {
                    echo "<h4>Kills/Deaths Ratio : <strong>0</strong></h4>";
                }
                echo "<h4>Chests Found : <strong>" . $assassin['chests_found'] . "</strong></h4>";
                echo "<h4>Blocks Walked : <strong>" . $assassin['blocks_walked'] . "</strong></h4>";
                echo "<h4>Logins : <strong>" . $assassin['logins'] . "</strong></h4>";
                echo "<h4>Deaths : <strong>" . $assassin['deaths'] . "</strong></h4>";
                echo "<h4>Level : <strong>" . $assassin['level'] . "</strong></h4>";
                if(!empty($assassind))
                {
                    echo "<h4><strong>=-= Dungeons Completed =-=</strong></h4>";
                    if(array_key_exists("Zombie", $assassind))
                    {
                        echo "<h4>Zombie Dungeon : <strong>" . $assassind['Zombie'] . "</strong></h4>";
                    }
                    if(array_key_exists("Animal", $assassind))
                    {
                        echo "<h4>Animal Dungeon : <strong>" . $assassind['Animal'] . "</strong></h4>";
                    }
                    if(array_key_exists("Skeleton", $assassind))
                    {
                        echo "<h4>Skeleton Dungeon : <strong>" . $assassind['Skeleton'] . "</strong></h4>";
                    }
                    if(array_key_exists("Silverfish", $assassind))
                    {
                        echo "<h4>Silverfish Dungeon : <strong>" . $assassind['Silverfish'] . "</strong></h4>";
                    }
                }
                ?>
            </div>
        </div>
    </div>
</div>
<?php
$knight = $playerData['classes']['knight'];
if(!is_null($knight['items_identified']))
{
    echo '<div class="row margin-panel"><div class="panel-default col-md-6 text-center"><div class="panel-heading"><h3 class="panel-title">Knight Statistics</h3></div><div class="panel-body opaque panel-height"><div class="list-scroll">';
    $knightd = $playerData['classes']['knight']['dungeons_completed'];
    echo "<h4>Items Identified : <strong>" . $knight['items_identified'] . "</strong></h4>";
    echo "<h4>Mobs Killed : <strong>" . $knight['mobs_killed'] . "</strong></h4>";
    echo "<h4>Nether Kills : <strong>" . $knight['pvp_kills'] . "</strong></h4>";
    echo "<h4>Nether Deaths : <strong>" . $knight['pvp_deaths'] . "</strong></h4>";
    if($knight['pvp_kills']!=0 && $knight['pvp_deaths']!=0)
    {
        $kd = (float)$knight['pvp_kills'] / (float)$knight['pvp_deaths'];
        echo "<h4>Kills/Deaths Ratio : <strong>" . number_format($kd, 2) . "</strong></h4>";
    }
    else
    {
        echo "<h4>Kills/Deaths Ratio : <strong>0</strong></h4>";
    }
    echo "<h4>Chests Found : <strong>" . $knight['chests_found'] . "</strong></h4>";
    echo "<h4>Blocks Walked : <strong>" . $knight['blocks_walked'] . "</strong></h4>";
    echo "<h4>Logins : <strong>" . $knight['logins'] . "</strong></h4>";
    echo "<h4>Deaths : <strong>" . $knight['deaths'] . "</strong></h4>";
    echo "<h4>Level : <strong>" . $knight['level'] . "</strong></h4>";
    if(!empty($knightd))
    {
        echo "<h4><strong>=-= Dungeons Completed =-=</strong></h4>";
        if(array_key_exists("Zombie", $knightd))
        {
            echo "<h4>Zombie Dungeon : <strong>" . $knightd['Zombie'] . "</strong></h4>";
        }
        if(array_key_exists("Animal", $knightd))
        {
            echo "<h4>Animal Dungeon : <strong>" . $knightd['Animal'] . "</strong></h4>";
        }
        if(array_key_exists("Skeleton", $knightd))
        {
            echo "<h4>Skeleton Dungeon : <strong>" . $knightd['Skeleton'] . "</strong></h4>";
        }
        if(array_key_exists("Silverfish", $knightd))
        {
            echo "<h4>Silverfish Dungeon : <strong>" . $knightd['Silverfish'] . "</strong></h4>";
        }
    }
    echo "</div></div></div>";

    echo '<div class="panel-default col-md-6 text-center"><div class="panel-heading"><h3 class="panel-title">Hunter Statistics</h3></div><div class="panel-body opaque panel-height"><div class="list-scroll">';
    $hunter = $playerData['classes']['hunter'];
    $hunterd = $playerData['classes']['hunter']['dungeons_completed'];
    echo "<h4>Items Identified : <strong>" . $hunter['items_identified'] . "</strong></h4>";
    echo "<h4>Mobs Killed : <strong>" . $hunter['mobs_killed'] . "</strong></h4>";
    echo "<h4>Nether Kills : <strong>" . $hunter['pvp_kills'] . "</strong></h4>";
    echo "<h4>Nether Deaths : <strong>" . $hunter['pvp_deaths'] . "</strong></h4>";
    if($hunter['pvp_kills']!=0 && $hunter['pvp_deaths']!=0)
    {
        $kd = (float)$hunter['pvp_kills'] / (float)$hunter['pvp_deaths'];
        echo "<h4>Kills/Deaths Ratio : <strong>" . number_format($kd, 2) . "</strong></h4>";
    }
    else
    {
        echo "<h4>Kills/Deaths Ratio : <strong>0</strong></h4>";
    }
    echo "<h4>Chests Found : <strong>" . $hunter['chests_found'] . "</strong></h4>";
    echo "<h4>Blocks Walked : <strong>" . $hunter['blocks_walked'] . "</strong></h4>";
    echo "<h4>Logins : <strong>" . $hunter['logins'] . "</strong></h4>";
    echo "<h4>Deaths : <strong>" . $hunter['deaths'] . "</strong></h4>";
    echo "<h4>Level : <strong>" . $hunter['level'] . "</strong></h4>";
    if(!empty($hunterd))
    {
        echo "<h4><strong>=-= Dungeons Completed =-=</strong></h4>";
        if(array_key_exists("Zombie", $hunterd))
        {
            echo "<h4>Zombie Dungeon : <strong>" . $hunterd['Zombie'] . "</strong></h4>";
        }
        if(array_key_exists("Animal", $hunterd))
        {
            echo "<h4>Animal Dungeon : <strong>" . $hunterd['Animal'] . "</strong></h4>";
        }
        if(array_key_exists("Skeleton", $hunterd))
        {
            echo "<h4>Skeleton Dungeon : <strong>" . $hunterd['Skeleton'] . "</strong></h4>";
        }
        if(array_key_exists("Silverfish", $hunterd))
        {
            echo "<h4>Silverfish Dungeon : <strong>" . $hunterd['Silverfish'] . "</strong></h4>";
        }
    }
    echo "</div></div></div>";
    echo "</div>";
}
?>
<?php
$wizard = $playerData['classes']['darkwizard'];
if(!is_null($wizard['items_identified']))
{
    echo '<div class="row margin-panel"><div class="panel-default col-md-6 text-center"><div class="panel-heading"><h3 class="panel-title">Darkwizard Statistics</h3></div><div class="panel-body opaque panel-height"><div class="list-scroll">';
    $wizardd = $playerData['classes']['darkwizard']['dungeons_completed'];
    echo "<h4>Items Identified : <strong>" . $wizard['items_identified'] . "</strong></h4>";
    echo "<h4>Mobs Killed : <strong>" . $wizard['mobs_killed'] . "</strong></h4>";
    echo "<h4>Nether Kills : <strong>" . $wizard['pvp_kills'] . "</strong></h4>";
    echo "<h4>Nether Deaths : <strong>" . $wizard['pvp_deaths'] . "</strong></h4>";
    if($wizard['pvp_kills']!=0 && $wizard['pvp_deaths']!=0)
    {
        $kd = (float)$wizard['pvp_kills'] / (float)$wizard['pvp_deaths'];
        echo "<h4>Kills/Deaths Ratio : <strong>" . number_format($kd, 2) . "</strong></h4>";
    }
    else
    {
        echo "<h4>Kills/Deaths Ratio : <strong>0</strong></h4>";
    }
    echo "<h4>Chests Found : <strong>" . $wizard['chests_found'] . "</strong></h4>";
    echo "<h4>Blocks Walked : <strong>" . $wizard['blocks_walked'] . "</strong></h4>";
    echo "<h4>Logins : <strong>" . $wizard['logins'] . "</strong></h4>";
    echo "<h4>Deaths : <strong>" . $wizard['deaths'] . "</strong></h4>";
    echo "<h4>Level : <strong>" . $wizard['level'] . "</strong></h4>";
    if(!empty($wizardd))
    {
        echo "<h4><strong>=-= Dungeons Completed =-=</strong></h4>";
        if(array_key_exists("Zombie", $wizardd))
        {
            echo "<h4>Zombie Dungeon : <strong>" . $wizardd['Zombie'] . "</strong></h4>";
        }
        if(array_key_exists("Animal", $wizardd))
        {
            echo "<h4>Animal Dungeon : <strong>" . $wizardd['Animal'] . "</strong></h4>";
        }
        if(array_key_exists("Skeleton", $wizardd))
        {
            echo "<h4>Skeleton Dungeon : <strong>" . $wizardd['Skeleton'] . "</strong></h4>";
        }
        if(array_key_exists("Silverfish", $wizardd))
        {
            echo "<h4>Silverfish Dungeon : <strong>" . $wizardd['Silverfish'] . "</strong></h4>";
        }
    }
    echo "</div></div></div>";

    echo '<div class="panel-default col-md-6 text-center"><div class="panel-heading"><h3 class="panel-title">Ninja Statistics</h3></div><div class="panel-body opaque panel-height"><div class="list-scroll">';
    $ninja = $playerData['classes']['ninja'];
    $ninjad = $playerData['classes']['ninja']['dungeons_completed'];
    echo "<h4>Items Identified : <strong>" . $ninja['items_identified'] . "</strong></h4>";
    echo "<h4>Mobs Killed : <strong>" . $ninja['mobs_killed'] . "</strong></h4>";
    echo "<h4>Nether Kills : <strong>" . $ninja['pvp_kills'] . "</strong></h4>";
    echo "<h4>Nether Deaths : <strong>" . $ninja['pvp_deaths'] . "</strong></h4>";
    if($ninja['pvp_kills']!=0 && $ninja['pvp_deaths']!=0)
    {
        $kd = (float)$ninja['pvp_kills'] / (float)$ninja['pvp_deaths'];
        echo "<h4>Kills/Deaths Ratio : <strong>" . number_format($kd, 2) . "</strong></h4>";
    }
    else
    {
        echo "<h4>Kills/Deaths Ratio : <strong>0</strong></h4>";
    }
    echo "<h4>Chests Found : <strong>" . $ninja['chests_found'] . "</strong></h4>";
    echo "<h4>Blocks Walked : <strong>" . $ninja['blocks_walked'] . "</strong></h4>";
    echo "<h4>Logins : <strong>" . $ninja['logins'] . "</strong></h4>";
    echo "<h4>Deaths : <strong>" . $ninja['deaths'] . "</strong></h4>";
    echo "<h4>Level : <strong>" . $ninja['level'] . "</strong></h4>";
    if(!empty($ninjad))
    {
        echo "<h4><strong>=-= Dungeons Completed =-=</strong></h4>";
        if(array_key_exists("Zombie", $ninjad))
        {
            echo "<h4>Zombie Dungeon : <strong>" . $ninjad['Zombie'] . "</strong></h4>";
        }
        if(array_key_exists("Animal", $ninjad))
        {
            echo "<h4>Animal Dungeon : <strong>" . $ninjad['Animal'] . "</strong></h4>";
        }
        if(array_key_exists("Skeleton", $ninjad))
        {
            echo "<h4>Skeleton Dungeon : <strong>" . $ninjad['Skeleton'] . "</strong></h4>";
        }
        if(array_key_exists("Silverfish", $ninjad))
        {
            echo "<h4>Silverfish Dungeon : <strong>" . $ninjad['Silverfish'] . "</strong></h4>";
        }
    }
    echo "</div></div></div>";
    echo "</div>";
}
?>
</div>
</div>
</div>
</body>
</html>
