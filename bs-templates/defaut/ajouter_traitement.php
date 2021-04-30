<?php
session_start();

// Module pour empecher le include d'être appelé directement
if (empty($variable_temoin))
{
exit("Quelque-chose me dit que vous n'avez rien à faire ici ?!");
} 
// Module pour empecher le include d'être appelé directement

include 'header.php';

$str = $_POST['sect']; // récupère le contenu 
$nom = htmlentities($str, ENT_QUOTES); // sécurise le contenu

$ajouter_traitement_hilde = "oui";
				
?>

<div id="page_content" class="pader">
	
	
<h1 class="align_center">Validation de l'inscription</h1>

<?php 

	if (isset($_SESSION['loggedin'])){ 
	
		$fastpass = "yes";
		echo site_register(1,$fastpass,$coins); // 175 = Nombre de caractères avant que le site ait une fiche dédiée
		$coins = $coins-1;
		
	}
	
	else {
		
		$fastpass = "no";
		echo site_register(175, $fastpass,'999'); // 175 = Nombre de caractères avant que le site ait une fiche dédiée
		
	}
	
?>

<?php if ($_SESSION['statut'] != "kaioh") {  ?>

	<?php if ($fastpass == "no") {  ?>

		<?php if($forcebacklink != "oui") { ?>
		
		<h2>Pourquoi souhaitons nous avoir un lien retour ?</h2>
		<p>Nous souhaitons améliorer la puissance SEO cet annuaire et ainsi indirectement améliorer votre référencement ! Plus il y aura de membres qui jouent le jeu en insérant un lien retour, plus cet annuaire aura du jus SEO et pourra booster votre site !</p>

		<h2>Exemple de lien retour que vous pouvez insérer :</h2>
		<table id="tab4backlink">
			<tr>
				<th>Prévisualisation</th><th>Code html</th>
			</tr>
			<tr>	
				<?php // Permet de varier les backlinks pour éviter de se prendre un pingoin de Google .. Les BL demandés seront affichés aléatoirement. PS : Vous pouvez ajouter autant de variante que vous voulez...
				 $num = Rand (1,3); 
				 switch ($num)
				 {
				 case 1:
				 $backlink = "<a href='$url_annuaire'>$titre_annuaire</a>";
				 break;
				 case 2:
				$backlink = "<a href='$url_annuaire'>Annuaire $titre_annuaire</a>";
				 break;
				 case 3:
				$backlink = "<a href='$url_annuaire'>Membre de $titre_annuaire</a>";
				 }
				 ?> 

			<td>
				<?php echo $backlink; ?>
			</td>
			<td>
				<textarea readonly="readonly" onclick="this.select()" class="backlinkcode"><?php echo $backlink; ?></textarea>
			</td>
			</tr>
		</table>
		
		<?php } ?>
		
		<?php 
		
		if ($module_de_paiement == TRUE) { 
		$refpaypal = valid_donnees($_POST["url"]); ?>
		
		<h2>🚀 Validation EXPRESS !</h2>
		<div class="info_jaune">
		<p>Vous n'avez pas le temps d'attendre ? Accélérez la validation et obtenez une <span style='color:red;font-size:150%;'>validation ultra rapide</span> via le module de paiement ci-dessous.</p></div>
		
		
		<?php 
		session_start(); // On relance la session pour le module de remerciement, car celle ci a été détruite et fermée par la fonction site_register.
		include 'payp_fl.php'; 
	
		} 
	
	}
	
	?>


<h3>🕸️ Poursuivez votre référencement !</h3>
<p>Poursuivez votre stratégie d'acquisition de backlinks en faisant des échanges sur des plateformes 100% GRATUITES comme <a href="https://janembart.com/go/dealerdetemps.php">Dealerdetemps.com</a>, en achetant des liens sur des sites +/- puissant sur <a href="https://janembart.com/go/semjuice.php">Semjuice</a> ou <a href="https://janembart.com/go/getfluence.php">Getfluence</a>, puis suivez vos performances avec <a href="https://janembart.com/go/ranxplorer.php">Ranxplorer</a> !</p>

<?php } ?>

</div>


<?php
		
include 'footer.php';

?>