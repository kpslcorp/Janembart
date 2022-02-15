<?php
// Module pour empecher le include d'être appelé directement
if (empty($variable_temoin))
{
exit("Quelque-chose me dit que vous n'avez rien à faire ici ?!");
} 
// Module pour empecher le include d'être appelé directement

$amp = $_GET["amp"]; 
if(isset($amp)) { 
	header("Status: 301 Moved Permanently", false, 301);
	header('Location: '.$url_annuaire.'search.html');
	}
include 'header.php';

?>
	
<div id="page_content">
	<h1>Rechercher un site</h1>
	
	<div id="breadcrumb">
	<p id="fil_ariane">
		<a href="<?php echo $url_annuaire; ?>">Annuaire</a> &gt; Rechercher
	</p>	
	</div>



<div id="j_join">
	<p style="text-align:center;">🔍 Vous voulez savoir si un site est déjà répertorié ? Utilisez notre moteur de recherche !</p>

<?php	
$resultats = "";
$resultats_title ="";

try
{
	$bddj = new PDO('mysql:host='.$serveur.';dbname='.$base_de_donnee.';charset=utf8', $nom_utilisateur, $pass, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));	
	$bddj->exec("set names utf8");
}
catch(Exception $e)
{
        die();
}

$query = trim(broken_html($_POST['search']));
$bind_slug = str_replace(" ","-",$query);

//traitement de la requête
if (isset($query) && !empty ($query)) {
	

	//Requête de sélection MySQL
	$list = $bddj->prepare("SELECT * FROM ".TABLE_SITE." WHERE (titre LIKE :query OR url LIKE :query) AND valide = 1 ORDER by titre");
	$list->bindValue(':query', '%'.$bind_slug.'%');
	$list->execute();

	//On compte les résultats
	$count = $list->rowCount();

	//On traite les résultats
	if ($count == 0) {
		
		$resultats_title = "❌ Aucun résultat n'a été trouvé";
		
		
	} else {
			
		$result_word = "résultat(s) trouvé(s)";
		$resultats_title = "$count $result_word : $query";

		$resultats .= "<table id='listisite' style='width:100%;margin-top:25px;'><thead><tr><td>Nom</td><td>Voir la fiche</td><td>Voir le site Web</td></tr></thead><tbody>";
		
		while ($data = $list->fetch(PDO::FETCH_OBJ)) {
			  
			$id = $data->id_site;
			$nom = stripslashes($data->titre);
			$url = $data->url;
			$level = $data->type;
			
			if ($level == 1) {
			
				$permanlink = rcp_site($id, "", "", "", "", "");
				foreach($permanlink as $s){
					$permanlink = $s['info']['permanlink'];
					$perman_build = "👁 <a href='$permanlink' target='_blank'>Voir en ligne</a>";
				}
				
				
			
			} else {
				
				$perman_build = "<abbr title='Pas de fiche dédiée pour cet enregistrement'>❌</abbr>";
				
			}
			
			$resultats .= "<tr><td><strong>$nom</strong></td><td>$perman_build</td><td>🌐 <a href='$url' target='_blank'>Ouvrir le site web</a></td></tr>";
			
		}
		
		$resultats .= "</tbody></table>";
		
	}

} else {
	
 $resultats_title = "Saisissez votre recherche :";
 
}

?>




		<h2><?php echo $resultats_title; ?></h2>

	
	<div class="box boxlist">
		
		<form method="post" id="searchitool">
		  <input type="search" name="search" placeholder="Entrez votre recherche:" style="width:100%;font-size:20px;padding:10px 0;text-indent:10px;" class="soumettre_input">
		  <input type="submit" name="submit" value="Chercher 🔍">
		</form>
		
		<?php echo $resultats; ?>
	
	</div>

	
</div>


</div>

<?php

include 'footer.php'; 