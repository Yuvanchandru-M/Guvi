<?php

require_once __DIR__. '/vendor/autoload.php';

$con = new MongoDB\Client("mongodb://localhost:27017");

$db = $con->guvi;

$tbl = $db->user_profile;

$tbl->findOne(['contact' =>'9876543210']);

var_dump($tbl);


?>