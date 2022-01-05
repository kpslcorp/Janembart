<?php
$currentfolder =  getcwd();
$xfiles = "$currentfolder/config.php"; // Construction du nom du fichier temporaire

if (!file_exists($xfiles)) {
	
	header("Location: install.php");

} else {
	
	session_start();
	header('Content-Type: text/html; charset=utf-8');
	ini_set( 'default_charset', 'utf-8' );
	error_reporting(0);
	
	include_once 'config.php';

	// Gestion des images en AMP

	$amp = $_GET["amp"]; 

	if((isset($amp)) AND ($disable_amp != 'oui')) {
		
		$amp=true;
		$imgtag_small = 'amp-img';
		$imgtag = 'amp-img layout="responsive"';
		$imgtag_vignette = 'amp-img layout="responsive" width="320" height="240"';
		$imgtag_close = "</amp-img>";
		$wizamp="amp";
			
	} else {
		
		$amp=NULL;
		$imgtag_small = 'img';
		$imgtag = "img";
		$imgtag_vignette = "img";
		$imgtag_close = "";
		$wizamp="";
		
	} 
		
	// Fin de la Gestion AMP

	$cache = 'cache/cachetoi'.sha1($_GET['act'] . $_GET['id'] . $_GET['p']. $_GET['c'].$_GET['format'].$wizamp);

	$act = htmlspecialchars($_GET['act']);

	if ($act == 'aj' || $act == 'ad' || $act == 'co' || $act == 'coj') {
		
		$expire = time() -1 ; // Tu as une seconde avant que je te kill ;) // Pour éviter qu'une erreur récurente soit sur la page de validation
	
	} else {
		
		$expire = time() -1800; // Tu as une demi-heure avant que je te kill ;)

	}

	if (($_COOKIE["admink"]) !== ($_SESSION["admink"])) { // Si Cookie et variables de sessions différentes => Déconnexion

		unset ($_SESSION["admink"]);
		unset ($_SESSION["loggedin"]);
		unset ($_COOKIE['admink']);
		
	} 

	if((file_exists($cache)) AND (filemtime($cache) > $expire) AND (!isset($_SESSION['loggedin']))) { // Si le cache existe, est encore valide, et qu'on est pas loggué ==> on lis le cache
		
			readfile($cache);
			
	} else { // Sinon on navigue sans cache.
		
		ob_start();

		include 'bs-includes/general.php';

		include 'bs-includes/tbl-mysql.php';

		// Gestion Paypal

		if ((!empty($paypal_id)) AND ($mode_vacances == FALSE)) {$module_de_paiement = TRUE;}
			
		// Fin de la gestion de Paypal

		if ((isset($_SESSION['loggedin'])) AND ($_SESSION['statut'] == "pr0")){ // Si Compte PRO (Client)
			$id_user = $_SESSION['id_user'];
			$coins = rcp_pro_credit($id_user); 
		}

		$variable_temoin = 1;
			
		//Page spéciale, sinon page d'accueil
		 if (isset($_GET['act'])){
			
			// Fiche site
			if(($_GET['act'])=='1') {
				include 'bs-templates/defaut/site.php';
			}
			
			//Section
			elseif(($_GET['act'])=='2') {
				include 'bs-templates/defaut/section.php';
			}
			
			//Categorie
			elseif(($_GET['act'])=='3') {
				include 'bs-templates/defaut/categorie.php';
			}
			
			//Page
			elseif(($_GET['act'])=='4') {
				include 'bs-templates/defaut/page.php';
			}
			
			//Ajouter
			elseif(($_GET['act'])=='ad'){
				include 'bs-includes/kaptchaa.php';
				include 'bs-templates/defaut/ajouter.php';
			}
			
			//Resultat de l'ajout
			elseif(($_GET['act'])=='aj'){
				include 'bs-templates/defaut/ajouter_traitement.php';
			}
			
			//Paramétrer mes sites pro
			elseif(($_GET['act'])=='parampro'){
				if ((isset($_SESSION['loggedin'])) AND ($_SESSION['statut'] == "pr0")){ 
					include 'bs-templates/defaut/parametrer.php';
				} else {
					exit("Erreur ! Merci de vous connecter sur votre espace PRO.");
				}
			}
			
			//Recharger des crédits
			elseif(($_GET['act'])=='recharger'){
				if ((isset($_SESSION['loggedin'])) AND ($_SESSION['statut'] == "pr0")){ 
					include 'bs-templates/defaut/recharger.php';
				} else {
					exit("Erreur ! Merci de vous connecter sur votre espace PRO.");
				}
			}
			
			//Self Pro
			elseif(($_GET['act'])=='selfpro'){
				if ((isset($_SESSION['loggedin'])) AND ($_SESSION['statut'] == "pr0")){ 
					include 'bs-templates/defaut/selfpro.php';
				} else {
					exit("Erreur ! Merci de vous connecter sur votre espace PRO.");
				}
			}
			
			//Self Pro Update
			elseif(($_GET['act'])=='selfproupdate'){
				if ((isset($_SESSION['loggedin'])) AND ($_SESSION['statut'] == "pr0")){ 
					include 'bs-templates/defaut/selfpro_update.php';
				} else {
					exit("Erreur ! Merci de vous connecter sur votre espace PRO.");
				}
			}
			
			//Self Pro Update Traitement
			elseif(($_GET['act'])=='selfproupdated'){
				if ((isset($_SESSION['loggedin'])) AND ($_SESSION['statut'] == "pr0")){ 
					include 'bs-templates/defaut/selfpro_update_traitement.php';
				} else {
					exit("Erreur ! Merci de vous connecter sur votre espace PRO.");
				}
			}
			
			//Accelerer Validation
			elseif(($_GET['act'])=='accelerer'){
					include 'bs-templates/defaut/accelerer.php';
			}
			
			//Merci
			elseif(($_GET['act'])=='merci'){
					include 'bs-templates/defaut/merci.php';
			}
			
			//Contact Form
			elseif(($_GET['act'])=='co'){
				include 'bs-includes/kaptchaa.php';
				include 'bs-templates/defaut/contact.php';
			}
			
			//Contact Traitement
			elseif(($_GET['act'])=='coj'){
				include 'bs-templates/defaut/contact2.php';
			}
			
			// Sitemap
			elseif(($_GET['act'])=='sitemap'){
				include 'bs-includes/sitemap.php';
			}
			
			//Flux RSS
			elseif(($_GET['act'])=='flux'){
				echo flux_rss();
			}
		
		} else {
			
			include 'bs-templates/defaut/accueil.php';
			
		 }

		mysqli_close ($connexion);
			
		$page = ob_get_contents();
			
		ob_end_clean();

		// Si on est pas connecté et que ce ne sont pas des pages dynamiques ou sensibles , alors on met en cache :
		if ((!isset($_SESSION['loggedin'])) AND ($_GET['act'] != "co") AND ($_GET['act'] != "coj") AND ($_GET['act'] != "ad") AND ($_GET['act'] != "aj") AND ($_GET['act'] != "parampro") AND ($_GET['act'] != "recharger") AND ($_GET['act'] != "selfpro") AND ($_GET['act'] != "selfproupdate") AND ($_GET['act'] != "selfproupdated") AND ($_GET['act'] != "accelerer") AND ($_GET['act'] != "merci")) { 

			file_put_contents($cache, $page);
			
		}
			
		echo $page;
	
	}
	
}