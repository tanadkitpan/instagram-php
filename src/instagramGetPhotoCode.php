<?php

$username = $_GET['username'];
$password = $_GET['password'];

$target = "";
$rur = "";
$ds_user_id = "";
$sessionid = "";
$mid = "";
$csrftoken = "";

require '../vendor/autoload.php';
require_once __DIR__ . '/InstagramScraper/connectdb.php';

$connect = \InstagramScraper\connectdatabase::getInstance();
$instagram = \InstagramScraper\Instagram::withCredentials($username, $password, 'path/to/cache/folder');
$session = $instagram->login();

$userAll = $connect->selecttUserCode();
$numLike = 5;

for ($k = 0; $k < sizeof($userAll); $k++) {

    $medias = $instagram->getMedias($userAll[$k], $numLike);
    
    $IDALL = [];
    $len = sizeof($medias);
    
    for ($i = 0; $i < $len; $i++) {
        $media = $medias[$i];
        $IDALL[] = $media->getID();
    }
    
    $connect->insertPhotoCode($username, $userAll[$k], $IDALL); 
    
}

echo "Get Photo Done";



