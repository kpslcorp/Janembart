<?php
session_start();

// Module pour empecher le include d'être appelé directement
if (empty($variable_temoin))
{
exit("Quelque-chose me dit que vous n'avez rien à faire ici ?!");
} 
// Module pour empecher le include d'être appelé directement

$amp = $_GET["amp"]; 
if(isset($amp)) { 
	header("Status: 301 Moved Permanently", false, 301);
	header('Location: '.$url_annuaire.'contact.html');
	}
include 'header.php';
$pb = $_GET['pb'];
$pb = valid_donnees($pb);
?>
	
<div id="page_content">
	<h1>Contactez-nous</h1>
	
	<div id="breadcrumb">
	<p id="fil_ariane">
		<a href="<?php echo $url_annuaire; ?>">Annuaire</a> &gt; Contact
	</p>	
	</div>


<?php if (is_numeric($pb)) { ?>


<div id="j_join">

	<form method="POST" action="contact2.html">

		<fieldset id="ajouter2">
		
		<h3>🚨 Je signale un site posant problème</h3>
		<p>Je souhaites signaler que le site ayant l'ID <?php echo $pb; ?> présente un problème</p>
		<p style="display:none;"><input value="<?php echo $pb; ?>" type="number" name="pb" placeholder="ID Problématique" required="required" class="soumettre_input" /></p>
		
		<h3>📧 Mon adresse email</h3>
		<p><input value="<?php if (!empty($_SESSION['kap_contactform_mail'])) {echo $_SESSION['kap_contactform_mail'];} ?>" type="mail" name="mail" placeholder="e-Mail"  required="required" class="soumettre_input" autocomplete="off" /></p>
		
		<h3>👀 Type de problème</h3>
		<script>
			function showHideEle(selectSrc, targetEleId, triggerValue) {	
				if(selectSrc.value==triggerValue) {
					document.getElementById(targetEleId).style.display = "block";
				} else {
					document.getElementById(targetEleId).style.display = "none";
				}
			} 
		</script>
		<p>
		<select name="yakoi" class="form__input" onchange="showHideEle(this, 'precisez', 'autre')">
							<option value="offline" selected="selected">Site off-line</option>
							<option value="spam">Site Spam</option>
							<option value="autre">Autre</option>
		</select>
		</p>
		
		
		<p style="display:none;" id="precisez"><textarea class="soumettre_textarea" name="precisez" style="font-size:18px;" placeholder="Merci de préciser le problème rencontré sur ce site"><?php if (!empty($_SESSION['kap_contactform_message'])) {echo $_SESSION['kap_contactform_message'];} ?></textarea></p>
		
		<h3>Répondez à la question de sécurité :</h3>
		
		<p><?php echo $question_q; ?></p>
		<p><input id="security_code" name="security_code" type="text" class="soumettre_input" required="required" autocomplete="off" /></p>
		
		<h3>Politique de Confidentialité</h3>
		
		<p><input type="checkbox" id="rpgd" name="rpgd" value="Consentement OK" required="required" /> <label for="rpgd">Ce site respecte le RGPD. Ceci étant, en soumettant ce formulaire, j'accepte que les informations saisies soient exploitées dans le cadre de ma demande afin de pouvoir obtenir une réponse par email dans les plus brefs délais.</label></p>

		<p><input value="Envoyer" type="submit" name="submit" /></p>
		
		</fieldset>
		
	</form>
	
</div>


<?php } else {?>

<div id="j_join">
	<p style="text-align:center;">📧 Une question ? Une suggestion ? Contactez-nous !</p>

	<form method="POST" action="contact2.html">

		<fieldset id="ajouter2">
		
		<p><input value="<?php if (!empty($_SESSION['kap_contactform_mail'])) {echo $_SESSION['kap_contactform_mail'];} ?>" type="mail" name="mail" placeholder="e-Mail"  required="required" class="soumettre_input" autocomplete="off" /></p>
	
		<p><input value="<?php if (!empty($_SESSION['kap_contactform_name'])) { echo $_SESSION['kap_contactform_name'];} ?>" type="text" name="nom" placeholder="Votre Nom"  required="required" class="soumettre_input" autocomplete="off" /></p>
					
		<p><input value="<?php if (!empty($_SESSION['kap_contactform_url'])) {echo $_SESSION['kap_contactform_url'];} ?>" type="url" name="url" placeholder="URL de votre site" class="soumettre_input" autocomplete="off" /></p>
		
		<p><textarea class="soumettre_textarea" name="contenu" style="font-size:18px;" required="required" placeholder="Votre Message" minlength="20"><?php if (!empty($_SESSION['kap_contactform_message'])) {echo $_SESSION['kap_contactform_message'];} ?></textarea></p>
		
		<h3>Répondez à la question de sécurité :</h3>
		
		<p><?php echo $question_q; ?></p>
		<p>
			<input id="security_code" name="security_code" type="text" class="soumettre_input" required="required" autocomplete="off" />
		</p>
		
		
		<h3>Politique de Confidentialité</h3>
		
		<p><input type="checkbox" id="rpgd" name="rpgd" value="Consentement OK" required="required" /> <input type="checkbox" id="cgv-u" name="cgv-u" /><label for="rpgd">Ce site respecte le RGPD. Ceci étant, en soumettant ce formulaire, j'accepte que les informations saisies soient exploitées dans le cadre de ma demande afin de pouvoir obtenir une réponse par email dans les plus brefs délais.</label></p>

		<p><input value="Envoyer" type="submit" name="submit" /></p>
		
		</fieldset>
		
	</form>
	
</div>

<?php } ?>

</div>

<?php

include 'footer.php'; 

?>