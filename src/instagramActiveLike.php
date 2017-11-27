<?php

$username = $_GET['username'];
$password = $_GET['password'];

$id = $_GET['id'];

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

$PhotoCode = $connect->selecttPhotoCode($id);
//sizeof($PhotoCode)
$sizeLike = 1000;
if($sizeLike > sizeof($PhotoCode)){
    $sizeLike = sizeof($PhotoCode);
}

for ($k = 0; $k < $sizeLike; $k++) {
    
    
    $status = $instagram->setMediaLikesByCode($PhotoCode[$k]);
    echo "[".$PhotoCode[$k]."]";
    if ($status === 200){
        $connect->updatePhotoCode($PhotoCode[$k]); 
    }
    Sleep(5);
    
}
echo "Done";



