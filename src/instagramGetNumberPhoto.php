<?php

$target = "";
$rur = "";
$ds_user_id = "";
$sessionid = "";
$mid = "";
$csrftoken = "";

require '../vendor/autoload.php';
require_once __DIR__ . '/InstagramScraper/connectdb.php';

$connect = \InstagramScraper\connectdatabase::getInstance();

$numUpdate = $connect->getNumUpdate();

echo "Get Number : " . $numUpdate;



