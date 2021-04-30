<?php
session_start();

// Module pour empecher le include d'être appelé directement
if (empty($variable_temoin))
{
exit("Quelque-chose me dit que vous n'avez rien à faire ici ?!");
} 
// Module pour empecher le include d'être appelé directement
$ajouter_traitement_hilde = "oui";

$amp = $_GET["amp"]; 
if(isset($amp)) { 
	header("Status: 301 Moved Permanently", false, 301);
	header('Location: '.$url_annuaire.'boostmywebsite.html');
	}
include 'header.php';

?>

<div id="page_content" class="pader">
	
	


<?php 

	if (isset($_SESSION['loggedin'])){ 
		
		$id_user = $_SESSION['id_user'];
		$mail_user = $_SESSION['mail_user'];
		$id_site = $_POST['id_site'];
		
		echo self_upgrade4pro ($id_user, $mail_user, $id_site);
		
		$coins = $coins-1;
		
		if ($coins <= 0) {
					
				echo"<div class='info_jaune'><p>⚠️ ATTENTION, vous avez actuellement <span style='color:red;font-size:150%;'>0 crédits</span> et vous ne pouvez donc pas bénéficier des avantages réservés aux comptes PREMIUM tant que vous n'avez pas chargé des unités sur votre compte.</p><p>Merci de <a href='contact.html'>nous contacter</a> au plus vite pour activer/renouveler vos avantages partenaires PREMIUM.</div>";
				
			if ($module_de_paiement == TRUE) { include 'payp.php'; } 
					
		}
	}
	
	else {
		
		exit ("Erreur, merci de vous reconnecter.");
		
	}
	
?>




</div>


<?php
		
include 'footer.php';

?>