<?php
session_start();
include('../config/config.php');
include('../Helpers/hashtag.php');
$hashtag = new Hashtag($con);
echo json_encode($hashtag->getTrending());
?>