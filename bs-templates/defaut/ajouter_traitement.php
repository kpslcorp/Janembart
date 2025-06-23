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
	
	
<h1 class="align_center">Etat de votre inscription</h1>

<?php 

	if (isset($_SESSION['loggedin'])){ 
	
		$fastpass = "yes";
		$result_site_register = site_register(1,$fastpass,$coins); // 175 = Nombre de caractères avant que le site ait une fiche dédiée
		$coins = $coins-1;
		
	}
	
	else {
		
		$fastpass = "no";
		$result_site_register = site_register(175, $fastpass,'999'); // 175 = Nombre de caractères avant que le site ait une fiche dédiée
		
	}
	
?>

<?php if ($_SESSION['statut'] != "kaioh") {  ?>

	<?php if ($fastpass == "no") {  ?>

		<?php 
		session_start(); // On relance la session pour le module de remerciement, car celle ci a été détruite et fermée par la fonction site_register.
		
		if (($module_de_paiement == TRUE) AND ($result_site_register != "kwak")){ 
		
			$refpaypal = valid_donnees($_POST["url"]); ?>
			
			<?php if ($payant_only_end_of_tunel) { // Mode paiement obligatoire en bout de tunnel ?>
			
				<div style="background: #eee; width: 80%; height: 8px; border-radius: 4px; overflow: hidden; margin: 20px auto; position: relative;">
				  <div style="height:100%; background:#4caf50; width:0%; animation: fillBar 1.4s ease-out forwards;"></div>
				</div>

				<style>
				@keyframes fillBar {
				  to { width: 50%; }
				}
				</style>

				<h2>⏳ Etape 2/2</h2>
				<div class="info_jaune">
				<p>Finalisez votre <span style='color: yellow;background: black; padding: 0 10px;'>soumission</span> maintenant via Paypal ou CB.</p>
				</div>
				

				<div style="font-family: 'Segoe UI', sans-serif; max-width: 460px; margin: 20px auto; border-radius: 16px; background: linear-gradient(135deg, #f9f9fb, #ffffff); box-shadow: 0 4px 20px rgba(0,0,0,0.08); padding: 20px; line-height: 1.5;">
				  <div style="font-size: 14px; color: #888; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;">Résumé</div>
				  <div style="font-size: 18px; font-weight: 600; margin-bottom: 12px;">
					Inscription de votre site<br><span style="font-size: 14px; font-weight: 400; color: #555;">sur notre annuaire en -48h</span>
				  </div>
				  <div style="display: flex; flex-direction: column; gap: 6px; font-size: 15px; margin-top: 12px;">
					<div style="display: flex; justify-content: space-between;"><span>Prix initial :</span> <span style="text-decoration: line-through; color: #888;">149,00 € TTC</span></div>
					<div style="display: flex; justify-content: space-between;"><span>Remise exceptionnelle :</span> <span style="color: #dc2626; font-weight: bold;">-60%</span></div>
					<div style="display: flex; justify-content: space-between;"><span>Prix HT :</span> <span>49,67&nbsp;€</span></div>
					<div style="display: flex; justify-content: space-between;"><span>TVA (20%) :</span> <span>9,93&nbsp;€</span></div>
					<div style="display: flex; justify-content: space-between; font-weight: bold; font-size: 16px;"><span>Total TTC :</span> <span>59,60&nbsp;€</span></div>
				  </div>
				</div>

				<?php include 'payp_fl.php'; ?>

				
			<?php } else { // Mode payant pour accélerer ?>
			
				<h2>🚀 Validation EXPRESS !</h2>
				<div class="info_jaune">
				<p>Vous n'avez pas le temps d'attendre ? Accélérez la validation et obtenez une <span style='color:red;font-size:150%;'>validation ultra rapide</span> via le module de paiement ci-dessous.</p>
				</div>
				<div class="info_jaune">
					<p>Validation Express = 59.60 EUR TTC (<em>49.67€HT</em>) ⤵️</p>
				</div>
				
				<?php include 'payp_fl.php'; ?>
				
			<?php } 
	
		} ?> 
		
		<?php 
		
		if ($forcebacklink != "oui" && !$payant_only_end_of_tunel) { ?>
		
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
	
	<?php }
	
?>


<h3>🕸️ Poursuivez votre référencement !</h3>
<p>Poursuivez votre stratégie d'acquisition de backlinks en faisant des échanges sur des plateformes 100% GRATUITES comme <a href="https://janembart.com/go/dealerdetemps.php">Dealerdetemps.com</a>, en achetant des liens sur des sites +/- puissant sur <a href="https://janembart.com/go/semjuice.php">Semjuice</a> ou <a href="https://janembart.com/go/getfluence.php">Getfluence</a>, puis suivez vos performances avec <a href="https://janembart.com/go/ranxplorer.php">Ranxplorer</a> !</p>

<?php } ?>

</div>


<?php
		
include 'footer.php';

?>