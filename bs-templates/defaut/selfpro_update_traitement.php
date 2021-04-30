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
	header('Location: '.$url_annuaire.'websiteupdated.html');
}
	

if (isset($_SESSION['loggedin'])){ 
		
		$id_user = $_SESSION['id_user'];
		$mail_user = $_SESSION['mail_user'];
		$id_du_site_sur_le_billard = $_POST['id_site'];
}
	
else {
		
		exit ("Erreur, merci de vous reconnecter.");
		
}

include 'header.php';

$str = $_POST['sect']; // récupère le contenu 
$nom = htmlentities($str, ENT_QUOTES); // sécurise le contenu

$ajouter_traitement_hilde = "oui";
				
?>

<div id="page_content" class="pader">
	
	
<h1 class="align_center">Traitement de votre demande</h1>

<?php 

		echo self_updatewebsite();
	
?>

				
		<h2>Aidez-nous à booster l'annuaire !</h2>
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
		


<h3>🚀 Poursuivez votre référencement !</h3>
<p>Poursuivez votre stratégie d'acquisition de backlinks en faisant des échanges sur des plateformes 100% GRATUITES comme <a href="https://janembart.com/go/dealerdetemps.php">Dealerdetemps.com</a>, en achetant des liens sur des sites +/- puissant sur <a href="https://janembart.com/go/semjuice.php">Semjuice</a> ou <a href="https://janembart.com/go/getfluence.php">Getfluence</a>, puis suivez vos performances avec <a href="https://janembart.com/go/ranxplorer.php">Ranxplorer</a> !</p>


</div>


<?php
		
include 'footer.php';

?>