<?php
require(dirname(__FILE__).'/../config.php');
session_start();
session_destroy();
unset($_COOKIE['admink']);
header("Location: $url_annuaire");
?>