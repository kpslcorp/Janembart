<?php
session_start();

// Module pour empecher le include d'être appelé directement
if (empty($variable_temoin))
{
exit("Quelque-chose me dit que vous n'avez rien à faire ici ?!");
} 
// Module pour empecher le include d'être appelé directement

include 'header.php';
?>
	
<div id="page_content">
	<h1>Contactez-nous</h1>
	
	<div id="breadcrumb">
	<p id="fil_ariane">
		<a href="<?php echo $url_annuaire; ?>">Annuaire</a> &gt; Contact
	</p>	
	</div>



<div id="j_join">

<?php 
$pb = valid_donnees($_POST["pb"]);
$nom = valid_donnees($_POST["nom"]);
$url = valid_donnees($_POST["url"]);
$maildugars = valid_donnees($_POST["mail"]);
$contenu = valid_donnees($_POST["contenu"]);
$rgpd = valid_donnees($_POST["rpgd"]);

if (!empty($pb)) {
	$yakoi = valid_donnees($_POST["yakoi"]);
	if ($yakoi == "autre") {
		$precisez= valid_donnees($_POST["precisez"]);
		if (empty($precisez)) {$precisez = "non renseigné";}
		}
	}


// Vérification du formulaire
$errors = []; // you can add errors to this array.
if (isset($_POST['submit']))
	{
		
		if (empty($pb)) { // Si ce n'est pas une déclaration de site posant problème on exige le nom
			if (empty($nom))
			{$errors[]= "Merci de saisir votre nom";} else {$_SESSION['kap_contactform_name'] = $nom;}
		}
	
		if (empty($maildugars))
		{$errors[]= "Merci de saisir une adresse mail";} else {$_SESSION['kap_contactform_mail'] = $maildugars;}
	
		if (filter_var($maildugars, FILTER_VALIDATE_EMAIL) == false)
		{$errors[]= "Votre adresse mail est incorrecte";} else {$_SESSION['kap_contactform_mail'] = $maildugars;}
	
		if (empty($pb)) { // Si ce n'est pas une déclaration de site posant problème on exige un message
			if (empty($contenu))
			{$errors[]= "Merci de saisir le contenu de votre mail";} else {$_SESSION['kap_contactform_message'] = $contenu;}
			if (strlen($contenu) < 20)
			{$errors[]= "Merci de saisir un texte un peu plus long... car là c'est plutôt suspect 🤔 !";} else {$_SESSION['kap_contactform_message'] = $contenu;}
		}
		
		if (!empty($pb)) { // Si c'est un problème, on exige de savoir lequel
			if (empty($yakoi))
			{$errors[]= "Merci de choisir quel est le problème que vous avez rencontré sur ce site.";} 
		}
	
		if( $_SESSION['kap_tcha_id'] == $_POST['security_code'] && !empty($_SESSION['kap_tcha_id'] ) ) {} 
		else {$errors[]= 'La réponse à la question secrète est incorrecte ? Seriez-vous un bot ?'; }
		
		if (scooter_form_malicious_scan($pb) == true) {$errors[]= 'Votre champ problème contient des caractères interdits';session_destroy ();}
		if (scooter_form_malicious_scan($maildugars) == true) {$errors[]= 'Votre champ mail contient des caractères interdits';session_destroy ();}
		if (scooter_form_malicious_scan($nom) == true) {$errors[]= 'Votre champ nom contient des caractères interdits';session_destroy ();}
		if (scooter_form_malicious_scan($url) == true) {$errors[]= 'Votre champ url contient des caractères interdits';session_destroy ();}
		if (scooter_form_malicious_scan($contenu) == true) {$errors[]= 'Votre champ contenu contient des caractères interdits';session_destroy ();}
		if (isset($_POST['cgv-u'])){$errors[]= "Vous avez un oeil bionique pour voir ce champ ?";session_destroy ();}
	}
	
	else 
		
	{
		{$errors[]= "Le formulaire est vide. Que faites-vous ici ?";}
	}
	
	
if (!empty($errors))
    {
		echo "<h2>Ouch !</h2><ul style='color:red;'>";
	
		foreach($errors as $valeur) 
		{  
			echo "<li>$valeur</li>";
		}
		echo "</ul><p style='text-align:center;'><a href='javascript:history.back()' class='form__submit'>Retour</a></p>";
    } 
	
	else {
		
		$lagestion = $url_annuaire."gestion/?act=1&id=".$pb;
		
		if (empty($pb)) {
			
				$sujet = "🔥 Message depuis mon annuaire $titre_annuaire";
				$nextplz = "✅ Demande bien envoyée";
				$message = $contenu;
				$message.= "<p>RGPD : $rgpd</p>";
				if (!empty($url)){
					$message .= "<p>URL : $url</p>";	
				}
				
			} else {
				
				$sujet = "⚠️ Un site semble poser problème sur mon annuaire $titre_annuaire"; 
				$nextplz = "✅ Signalement bien envoyé ! Merci :)";
				$message = "<h2 style='text-align:center;'>Le site #ID $pb pose problème</h2><p style='text-align:center;'><strong>Problème</strong> : $yakoi ! $precisez</p><p style='text-align:center;'><a href='$lagestion'>🥤 Administrer ce site</a></p><p style='text-align:center;'>Signalé par $maildugars<br />RGPD : $rgpd</p>";
			}
		
		
		$destinataire = $mail;
		$headers = 'Mime-Version: 1.0'."\r\n";
		$headers .= 'Content-type: text/html; charset=utf-8'."\r\n";
		$headers .= "From: $titre_annuaire <$mail>"."\r\n";
		$headers .= "Reply-To: $nom <$maildugars>"."\r\n";
		
		mail($destinataire, $sujet, $message, $headers);

		echo "<h2>$nextplz</h2><p style='text-align:center;'>Nous y répondrons dans les plus brefs délais.</p>";
		
		session_destroy ();

	}

?>
	
</div>

</div>

<?php

include 'footer.php'; 

?>