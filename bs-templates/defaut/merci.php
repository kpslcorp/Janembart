<?php
session_start();

// Module pour empecher le include d'être appelé directement
if (empty($variable_temoin))
{
	exit("Quelque-chose me dit que vous n'avez rien à faire ici ?!");
} 
// Module pour empecher le include d'être appelé directement


// Module pour empecher la page merci d'etre affiché par des bots
$k = valid_donnees($_GET["k"]);
$key = $_SESSION["lightkey"];
$k .= $key;
if (isset($_COOKIE["PaypalThx"])) {$cookie = $_COOKIE['PaypalThx'].$key;} else {$cookie = NULL;}

$id = (int) valid_donnees($_GET["id"]);
$pt = valid_donnees($_GET["pt"]);


if ($_COOKIE["PaypalThx"] == "sechgut") {		// Si la page merci a déjà été affichée => Redirection vers la HP (Chek par cookie)

		header("Status: 301 Moved Permanently", false, 301);
		header('Location: '.$url_annuaire.''); 
		exit();
		
} else { // Sinon on check si tout est ok ==> Sinon, redirection vers la HP
	
	
	// Module pour empecher la page merci d'etre affiché par des bots / spammeurs
	if ((is_null($cookie)) OR (empty($k)) OR (empty($key)) OR (empty($id)) OR (empty($pt)) OR ($cookie != $k )) {
		
		header("Status: 301 Moved Permanently", false, 301);
		header('Location: '.$url_annuaire.''); 
		exit();
		
	}
	// Module pour empecher la page merci d'etre affiché par des bots

	include 'header.php';

	if ($pt == 1) { // Si recharge de crédits pour compte pro
		$pay_type = "recharge de crédits sur un compte pro";$idtype="ID du Webmaster :";$complementmail=NULL;
	} elseif ($pt == 2) { // Si Validation Express
		$pay_type = "validation rapide";$idtype="Ref du site à booster :";$complementmail="<p>💡 Pensez à valider ce site rapidement mais aussi a lui booster sa note !</p>";
	} else {
		exit("BIENNNN ?!");
	}

	// On prévient l'admin par mail
	$destinataire = $mail;
	$sujet = "🦀💲🥂 Un paiement a été effectué sur $titre_annuaire 🥂💲🦀";
	$headers = 'Mime-Version: 1.0'."\r\n";
	$headers .= 'Content-type: text/html; charset=utf-8'."\r\n";
	$headers .= "From: $titre_annuaire <$mail>"."\r\n";
	
	$message = "<h1>🦀💲🥂 Bonne nouvelle 🥂💲🦀</h1><p>Un paiement Paypal vient d'être effectué sur $url_annuaire - <em>en tout cas la page de remerciement du client s'est déclenchée</em> - à toi de vérifier !</p><ul><li>Il s'agit d'un paiement pour une <strong>$pay_type</strong></li><li>$idtype $id</li></ul>$complementmail";
				
	mail($destinataire, $sujet, $message, $headers);
	// On prévient l'admin par mail
	
	
	// Passage au statut 4 Paiement à priori effectué, a confirmer
	$sql_go_to_step_4 = "UPDATE ".TABLE_SITE." SET valide='4' WHERE id_site = '".$id."'";
	$res = mysqli_query($connexion,$sql_go_to_step_4);
	
	setcookie("PaypalThx","sechgut", time()+3600, "/");	// On paramètre le cookie pour empecher le rechargement de la page
	?>
			 
	<div id="page_content">
		<h1>✅ Merci</h1>
		
		<div id="breadcrumb">
		<p id="fil_ariane">
			<a href="<?php echo $url_annuaire; ?>">Annuaire</a> &gt; Merci
		</p>	
		</div>
		
		<?php if ($module_de_paiement == TRUE) { 
		
			echo "<div class='info_jaune'><p>Votre paiement a bien été effectué. Vous allez recevoir sous peu de temps un mail de confirmation de la part de Paypal et votre commande sera honorée dans les plus brefs délais.</p></div>";
			
		} else {
			
			echo "<div class='info_jaune'><p>Le module de paiement n'est pas actif actuellement. N'hésitez pas à <u><a href='contact.html'>nous contacter</a></u> pour obtenir plus d'infos.</p></div>";
			
		}?>

	</div>


<?php

}

include 'footer.php'; 
