<?php

$username = $_GET['username'];
$password = $_GET['password'];


echo $username;
echo " - ";
echo $password;
exit(0);

$target = "";
$rur = "";
$ds_user_id = "";
$sessionid = "";
$mid = "";
$csrftoken = "";




require '../vendor/autoload.php';

$instagram = \InstagramScraper\Instagram::withCredentials($username, $password, 'path/to/cache/folder');
$session = $instagram->login();

$info = explode(";", $session["cookie"]);

for ($i = 0; $i < sizeof($info); $i++) {
    $text = $info[$i];
    if (strpos($text, "target") !== false) {
        $target = str_replace("target=", "", trim($text));
    } else if (strpos($text, "rur") !== false) {
        $rur = str_replace("rur=", "", trim($text));
    } else if (strpos($text, "ds_user_id") !== false) {
        $ds_user_id = str_replace("ds_user_id=", "", trim($text));
    } else if (strpos($text, "sessionid") !== false) {
        $sessionid = str_replace("sessionid=", "", trim($text));
    } else if (strpos($text, "mid") !== false) {
        $mid = str_replace("mid=", "", trim($text));
    } else if (strpos($text, "csrftoken") !== false) {
        $csrftoken = str_replace("csrftoken=", "", trim($text));
    }
}

$startUsername = 700;
$followersAll = $instagram->getFollowers($ds_user_id, ($startUsername + 100), (($startUsername + 100) / 10), true); //ดึงข้อมูลรูปของเพื่อน

if ($startUsername > sizeof($followersAll)) {
    $startUsername = sizeof($followersAll);
}


$end = $startUsername + 100;
for ($k = $startUsername; $k < $end; $k++) {

    $username = $followersAll[$k]["username"];
    
    $numLike = 1;
    $medias = $instagram->getMedias($username, $numLike); //ดึงข้อมูลรูปของเพื่อน

    $IDALL = [];
    $len = sizeof($medias);
    if ($len > $numLike) {
        $len = $numLike;
    }

    $lenLike = $numLike;
    if ($lenLike > $len) {
        $lenLike = $len;
    }

    for ($i = 0; $i < $len; $i++) {
        $media = $medias[$i];
        $IDALL[] = $media->getID();
    }

    for ($i = 0; $i < $lenLike; $i++) {

        Sleep(2);
        $status = $instagram->setMediaLikesByCode($IDALL[$i]);
        print_r("[" . $username . "-" . $IDALL[$i] . "-" . $status->code . "] ");
    }
}