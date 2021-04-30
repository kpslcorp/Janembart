<?php
session_start();

$php8_session_loggedin = isset($_SESSION['loggedin']) ? $_SESSION['loggedin'] : NULL;
$php8_session_statut = isset($_SESSION['statut']) ? $_SESSION['statut'] : NULL;
$php8_session_admink = isset($_SESSION['admink']) ? $_SESSION['admink'] : NULL;
$php8_cookie_admink = isset($_COOKIE["admink"]) ? $_COOKIE['admink'] : NULL;

if (!isset($php8_session_loggedin) OR ($php8_session_statut == "pr0")) { // Si pas loggués => Login.php
	header('Location: login.php');
	exit();
}

if (($php8_cookie_admink) !== ($php8_session_admink)) { // Si Cookie et variables de sessions différentes => Déconnexion
	$title = "Vous avez été déconnecté";
	include ('head.php');
	echo "<h1>Votre session a expiré</h1><p style='text-align:center;'>🐱‍👤 Toutes les bonnes choses ont une fin, <a href='login.php'>Veuillez vous reconnecter</a></p>";
	include ('foot.php');
	session_destroy();
	unset ($_COOKIE['admink']);
	exit();
} 

header('Content-Type: text/html; charset=utf-8'); // écrase l'entête utf-8 envoyé par php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
ini_set( 'default_charset', 'utf-8' );

require(dirname(__FILE__).'/../config.php');

require(dirname(__FILE__).'/../bs-includes/general.php');

require(dirname(__FILE__).'/../bs-includes/tbl-mysql.php');

include 'part/header.php';

$aiguilleur = isset($_GET['act']) ? $_GET['act'] : NULL;

if ($aiguilleur=='1') {

	include 'part/site.php';
	
}

elseif($aiguilleur=='8'){

	include 'part/categorie.php';

}

elseif($aiguilleur=='9'){

	include 'part/page.php';

}

elseif($aiguilleur=='99'){

	include 'part/userpro.php';

}

elseif($aiguilleur=='67'){

	include 'part/relance.php';

}

elseif($aiguilleur=='cache'){

	vider_cache('../cache');
	
	echo '<p style="text-align:center;">🌱 Cache vidé avec succès 🌱</p>'; 
	
}

else {

	include 'part/home.php';
	
}

	include 'part/colonne.php';

?>