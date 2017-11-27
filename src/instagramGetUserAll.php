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

$followersAll = $instagram->getFollowers($ds_user_id, 10000, 1000, true);

$userAll = [];
$k = 0;
for ($k = 0; $k < sizeof($followersAll); $k++) {

    $userAll[] = $followersAll[$k]["username"];
}

$connect->insertUserCode($username,$userAll,$k);

echo "Get my followers done!";