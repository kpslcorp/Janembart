<?php
require(dirname(__FILE__).'/config.php');

header("Status: 301 Moved Permanently", false, 301);
header("Location: $url_annuaire");
exit();
?>