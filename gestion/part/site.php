<?php 
//////////////////////
// Ajouter/Modifier un site
///////////////////////
$aiguilleur = isset($_GET['f']) ? $_GET['f'] : NULL;
if ($aiguilleur=='11') { 

foreach($_POST as $index => $valeur) {
			$$index = mysqli_real_escape_string($connexion,trim($valeur));
		}
		
		$site = rcp_site(intval($_GET['id']), "", "", "", "", "");
		foreach($site as $s){}
		
		$reinject_date = new DateTime($s['v_date']);
		$date2validation = $reinject_date->format('Y-m-d H:i:s'); 
		
		if ($s['valide'] == 2) {$date2validation = date("Y-m-d H:i:s");} 
		
		$sql = "UPDATE ".TABLE_SITE." SET titre='".$titre."',  type='".$type."',  url='".$url."', ancre='".$ancre."', url_retour='".$url_retour."', url_rss='".$url_rss."', sect='".$sect."', description='".convert_br($description, 0)."', note='".$note."', valide='1', date2validation='$date2validation' WHERE id_site = '".$id."'";
		  	
		$res = mysqli_query($connexion,$sql);
	
		if ($res) { // Si Requete SQL OK ?
		
			if ($s['valide'] == 2) { // On envoie un mail lors de la première validation du site, mais pas pour les futures modifications.
				if(mail_inscription_accepte()) {
					echo '<p class="align_center">📧 Mail envoyé</p>';
				}
			}
			vider_cache('../cache');
			
			?>
			
			
			<h2>✅ GREAT DUDE ! Votre demande a bien été prise en compte !</h2>
			
			<p class="align_center">Que voulez-vous faire maintenant ?</p>
			
			<p class="align_center">👀 <a href='<?php echo $s['info']['permanlink']; ?>' target='_blank' rel='nofollow noreferrer noopener'>Voir la fiche site (coté internaute)</a></p> 
			
			<p class="align_center">↩️ <a href='javascript:history.go(-1)'>Retourner sur la fiche site (coté admin)</a></p>

			<p class="align_center">👑 <a href="gestion">Valider ou Supprimer d'autres sites en attente.</a></p>
			
			<?php
			
		  }
		  
		  else { // Si requete SQL échoue
				echo "<p class='align_center'>Il y a eu une erreur lors de l'enregistrement : <br />";
				echo mysqli_error($connexion);
				echo "<br />Si l'erreur persiste merci de le signaler au <a href='mailto:help@janembart.com'>support de Janembart</a></p></p>";
		  }
}

//////////////////////
// Supprimer un site
///////////////////////

elseif($aiguilleur=='31'){ 

	$site = rcp_site(intval($_GET['id']), "", "", "", "", "");
							
		foreach($site as $s){
		
		$res = mysqli_query($connexion,"DELETE FROM ".TABLE_SITE." WHERE id_site ='".intval($_GET['id'])."'"); 
		  
		  

	if ($res) {
		
		if (($s['valide'] == 2) AND (!isset($_POST['maraboulet']))) { // Si le site n'a jamais été validé on envoie un mail de refus, si suppression ultérieure {ou site de marabout} on envoie rien !

			if(!mail_inscription_refuse()) {
				echo "<p class='align_center'>🤓 Echec de l'envoi du mail de suppression 🤓</p>";
			}
		}

		vider_cache('../cache');
?>
<p class="align_center">📛 Site supprimé avec succès.</p>
<p class="align_center"><a href="gestion/">🔥 Valider ou Supprimer d'autres sites en attente.</a></p>
<?php

		  } else {
			echo "<p>Il y a eu une erreur lors de la supression : <br />";
			echo mysqli_error($connexion);
			echo "<br />Si l'erreur persiste merci de le signaler au <a href='mailto:help@janembart.com'>support de Janembart</a></p>";
		  }
		  
	}
}

//////////////////////
// Relance BL Supprimé
///////////////////////

elseif($aiguilleur=='666'){ 

	$site = rcp_site(intval($_GET['id']), "", "", "", "", "");
	foreach($site as $s){
		$destinataire = $s['mail_auteur'];
	}
	
	$sujet = "💥 Re : [URGENT] Retrait du lien retour $titre_annuaire 💥";
	$contenu_du_mail_relance = $_POST['relancebl'];
	
	$anchorback = $_POST['ancre_retour_souhaitee'];
	$urlback = $_POST['url_retour_souhaitee'];
	$generation_code_souhaite = "&lt;a href='$urlback'>$anchorback&lt;/a>";
	

	$contenu_du_mail_relance = str_replace('%BACKLINK_SOUHAITE%', $generation_code_souhaite, $contenu_du_mail_relance);
	
	$headers = 'Mime-Version: 1.0'."\r\n";
	$headers .= 'Content-type: text/html; charset=utf-8'."\r\n";
	$headers .= "From: $titre_annuaire <$mail>"."\r\n";
	$headers .= "Reply-To: $titre_annuaire <$mail>"."\r\n";
	
	mail($destinataire, $sujet, $contenu_du_mail_relance, $headers);
	
	// Insertion SQL
	$misterfreeze = valid_donnees($_GET['id']);
	$dateduticket = date('Y-m-d');
	$insert_relance_sql = "INSERT INTO ".TABLE_RELANCE." VALUES ( '', '$misterfreeze', '$dateduticket')";
	$insert_relance = mysqli_query($connexion,$insert_relance_sql);
	
	if ($insert_relance) {
			echo "<h2>✅ Relance envoyée ! Y a plus qu'à attendre !</h2>";
			echo '<p style="padding;10px;text-align:center"><a href="gestion/?act=67">Voir la liste des tickets</a></p>';
		} else {
			echo mysqli_error();
		}
	
}

//////////////////////
// Rappel Avantages Lien Retour
///////////////////////

elseif($aiguilleur=='667'){ 

	$site = rcp_site(intval($_GET['id']), "", "", "", "", "");
	foreach($site as $s){
		$destinataire = $s['mail_auteur'];
	}
	
	$sujet = "💥 Boostez [GRATUITEMENT] votre site sur $titre_annuaire 💥";
	$contenu_du_mail_relance = $_POST['relancebl'];
	
	$anchorback = $_POST['ancre_retour_souhaitee'];
	$urlback = $_POST['url_retour_souhaitee'];
	$generation_code_souhaite = "&lt;a href='$urlback'>$anchorback&lt;/a>";
	

	$contenu_du_mail_relance = str_replace('%BACKLINK_SOUHAITE%', $generation_code_souhaite, $contenu_du_mail_relance);
	
	$headers = 'Mime-Version: 1.0'."\r\n";
	$headers .= 'Content-type: text/html; charset=utf-8'."\r\n";
	$headers .= "From: $titre_annuaire <$mail>"."\r\n";
	$headers .= "Reply-To: $titre_annuaire <$mail>"."\r\n";
	
	mail($destinataire, $sujet, $contenu_du_mail_relance, $headers);
	
	echo "<h2>✅ Mail Envoyé ! Y a plus qu'à attendre !</h2><p style='text-align:center;'><a href='javascript:history.go(-1)'>Retour</a></p>";
	
}



///////////////////////////
// Rechercher un site
///////////////////////////

elseif($aiguilleur=='2501'){ 


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
	$list = $bddj->prepare("SELECT * FROM ".TABLE_SITE." WHERE titre LIKE :query OR url LIKE :query");
	$list->bindValue(':query', '%'.$bind_slug.'%');
	$list->execute();

	//On compte les résultats
	$count = $list->rowCount();

	//On traite les résultats
	if ($count == 0) {
		
		$resultats_title = "Aucun résultat n'a été trouvé";
		
		
	} else {
			
		$result_word = "résultat(s) trouvé(s)";
		$resultats_title = "$count $result_word : $query";

		$resultats .= "<table id='listisite' style='width:100%;margin-top:25px;'><thead><tr><td>Nom</td><td>Coté Client</td><td>Coté Webmaster</td><td>Site Web</td></tr></thead><tbody>";
		
		while ($data = $list->fetch(PDO::FETCH_OBJ)) {
			  
			$id = $data->id_site;
			$nom = $data->titre;
			$url = $data->url;
			$level = $data->type;
			
			if ($level == 1) {
			
				$permanlink = rcp_site($id, "", "", "", "", "");
				foreach($permanlink as $s){
					$permanlink = $s['info']['permanlink'];
					$perman_build = "👁 <a href='$permanlink' target='_blank'>Voir en ligne</a>";
				}
				
			
			} else {
				
				$perman_build = "❌";
				
			}
			
			$resultats .= "<tr><td><strong>$nom</strong></td><td>$perman_build</td><td>⚙️ <a href='gestion/?act=1&id=$id'>Editer</a></td><td>🌐 <a href='$url' target='_blank'>Ouvrir le site web</a></td></tr>";
			
		}
		
		$resultats .= "</tbody></table>";
		
	}

} else {
	
 $resultats_title = "Rechercher un site";
 
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




<?php }

///////////////////////////
// Afficher tous les sites
///////////////////////////

elseif($aiguilleur=='6'){ 

?>
	<div>
		<div class="titre_colonne" style="margin-top:5px">Tous les sites</div>
		<table width="100%" id="listisite" class="doublesaut">
			<thead>
			<tr>
				<th style="text-align:center;width:6%">ID<br /><a href="gestion/?act=1&f=6&order=id_site-ASC">&uArr;</a> <a href="gestion/?act=1&f=6&order=id_site-DESC">&dArr;</a></th>
				<th align="center">✅️</th>
				<th>🌐 URL <a href="gestion/?act=1&f=6&order=url-ASC">&uArr;</a> <a href="gestion/?act=1&f=6&order=url-DESC">&dArr;</a> | 🏷️ Nom <a href="gestion/?act=1&f=6&order=titre-ASC">&uArr;</a> <a href="gestion/?act=1&f=6&order=titre-DESC">&dArr;</a></th>
				<th>✉️ Mail <a href="gestion/?act=1&f=6&order=mail_auteur-ASC">&uArr;</a> <a href="gestion/?act=1&f=6&order=mail_auteur-DESC">&dArr;</a></th>
				<td>🔗<br /><a href="gestion/?act=1&f=6&order=url_retour-ASC">&uArr;</a> <a href="gestion/?act=1&f=6&order=url_retour-DESC">&dArr;</a></td>
				<th style="text-align:center;width:8%">⭐<br /><a href="gestion/?act=1&f=6&order=note-ASC">&uArr;</a> <a href="gestion/?act=1&f=6&order=note-DESC">&dArr;</a></th>
				<th align="center">🗑️</th>
			</tr>
			</thead>
			<tbody>
			<?php

			// Gestion du tri
			if(!empty($_GET['order'])){
				
				$get_order = str_replace('-', ' ', strip_tags($_GET['order']));
				
			} else {
				
				$get_order = 'id_site DESC';
				
			}
			
			// Pagination
			$ma_limite = $_GET['limit'];
			if(isset($ma_limite)) {
				
				$limit = intval($ma_limite)+100;
				
			} else {
				
				$limit = '100';
			
			}
			
			// Nombre de sites en attente + validés
			$sitelimit = stat_site (1, '') + stat_site (2, '');
			
			// Recuperation de la liste
				
			$site = rcp_site('', '', 'ORDER BY '.$get_order.' LIMIT '.intval($ma_limite).', '.$limit, '', '', '');
			
			require_once dirname(__FILE__).'/../../bs-includes/email_blacklist.php';
						
			if (!empty($site)) {
				
				foreach($site as $s){
					
					$mailauteur = $s['mail_auteur'];
				
					if (in_array($mailauteur, $email_colimateur)) {
						
						$spot_relou = "⚠️";
						
					} else {
						
						$spot_relou = "";
						
					}
				
			?>
			
			<tr <?php if ($s['valide'] == 2){ ?>style="opacity:0.5;"<?php } ?>>
				<td colspan="8"><a class='thuglife' href="<?php echo $s['url']; ?>" target='_blank' rel='nofollow noreferrer noopener' ><?php echo url_www($s['url']); ?></a></td>
			</tr>
			<tr <?php if ($s['valide'] == 2){ ?>style="opacity:0.5;"<?php } ?>>
				<td><?php echo $s['id_site']; ?></td>
				<td><?php if ($s['valide'] == 1){ ?><a href="<?php echo $s['info']['permanlink'];?>" target='_blank' rel='nofollow noreferrer noopener'>✅️</a><?php } else { echo '⌛'; } ?></td>
				<td><a class='maildepot' href="gestion/?act=1&id=<?php echo $s['id_site']; ?>" title="<?php $date = new DateTime($s['f_date']);echo "⌛ ". $date->format('d/m/Y');if ($s['valide'] == 1) { 
				$v_date = new DateTime($s['v_date']); echo " ☑️ ". $v_date->format('d/m/Y');} ?>">⚙️ <?php echo stripslashes($s['titre']); ?></a></td>
				<td><a class='maildepot' href="mailto:<?php echo $mailauteur; ?>"><?php echo $mailauteur; ?></a></td>
				<?php $mister_bl = $s['url_retour']; ?>
				<td><?php if (empty($mister_bl)){ echo '⭕'; } else { echo "<a href='$mister_bl' target='_blank' rel='nofollow noreferrer noopener'>✅</a>"; } ?></td>
				<td><?php echo $s['note']; ?></td>
				<td><a href="gestion/?act=1&f=31&id=<?php echo $s['id_site']; ?>" onclick="return confirm('Etes-vous sur de vouloir supprimer <?php echo stripslashes($s['titre']); ?> de votre annuaire ?')">🗑️</a></td>
			</tr>

			<?php 
			
				unset($mailauteur); 
				
				}
			}
			
			?>
			</tbody>
		</table>
		<?php if ($sitelimit > 99) { // Si plus de 99 sites, on affiche un lien vers la page 2, 3, 4... Chaque page affichant 100 sites ?>
			<p style="margin:20px 0; text-align:center;"><a href="<?php echo '//'.$_SERVER['HTTP_HOST'].preg_replace('(&limit=.*)', '', $_SERVER['REQUEST_URI']).'&limit='.$limit; ?>">⏭️ Afficher la suite</a></p>
		<?php } ?>
	</div>

<?php
}

////////////////
//// Fiche Site
///////////////

elseif(intval($_GET['id'])>0) { // Fiche Site

	$site = rcp_site(intval($_GET['id']), '', '', '', '', '');
							
	foreach($site as $s){
	
		$sect = rcp_sect($s['sect'], "", "");
						
			foreach($sect as $se){
						
				$cat = rcp_cat($se['id_cat'], "");
						
					foreach($cat as $c){

									?>


	<form method="POST" action="gestion/?act=1&f=11&id=<?php echo $s['id_site']; ?>">

	<fieldset id="ajouter2">
	<table width="100%" class="formulaire" style="margin-top:20px;">
		<tr>
		
			<td class="align_left" width="20%">
			
				<label>📅 Proposé <?php if ($s['valide'] == 1) { echo "/ Validé "; } ?>:</label></td>
				
			<td>
			
				<?php 
					$date = new DateTime($s['f_date']);
					echo "⌛ ". $date->format('d/m/Y');
					if ($s['valide'] == 1) { 
						$v_date = new DateTime($s['v_date']); echo " ☑️ ". $v_date->format('d/m/Y'); // Si site validé, on affiche aussi sa date de validation ?>
						<a href="<?php echo $s['info']['permanlink']; ?>" target="_blank">🌍</a>
					<?php } ?> 
			</td>
				
		</tr>
		<tr style="line-height:30px;">
		
			<td class="align_left" width="25%">
			
				<label for="mail_auteur">✉️ Mail :</label>
				
			</td>
			<td>
			
				<input type="hidden" value="<?php echo $s['mail_auteur']; ?>" name="mail_auteur" />
				
				<a href="mailto:<?php echo $s['mail_auteur']; ?>"><?php echo $s['mail_auteur']; ?></a> 
				
			</td>
			
		</tr>
		
		<?php if ($s['valide'] == 2) { // Valable lorsque le site n'a pas encoré été accepté/refusé mais pas pour les futures modifications. ?>
		<tr>
		
			<td colspan="2" class="conseltd">
			
				<span class="conseil">⬆️  Votre décision (acceptation/refus) sera envoyé automatiquement dans cette boite mail sauf si vous activez le mode suppression ninja ci-dessous 🐱‍👓</span>
				
			</td>
			
		</tr>
		
		<?php } ?>
		
		<tr>
			
			<td class="align_left" width="25%">
				
				<label for="url">📛 Titre du site :</label>
				
			</td>
			
			<td>
			
				<input value="<?php echo stripslashes($s['titre']); ?>" type="text" name="titre" style="width : 400px" class="soumettre_input" />
				
			</td>
		</tr>
		
		<tr>
			
			<td class="align_left" width="25%">
    
				<label for="url">🌐 URL du site :</label></td>
			
			<td>
			
				<input value="<?php echo $s['url']; ?>" type="text" name="url" style="width : 400px" class="soumettre_input" /> <a href="<?php echo $s['url']; ?>" target="_blank"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEAAAABACAYAAACqaXHeAAACf0lEQVR4Xu2av04VQRSHPyTRWPgAJCa2QKT3HRQLhBYSwhtoCw01vY3RzkKEAnkHekKoKKx8ACtM/JOT7JrNZnZ3ZnfO3bOzc5tb3Nmz5/fNOb+duTtLzPyzNHP9ZAC5AmZOILfAzAsgm2BICzwDjoAXwCrwIFL1uHI4Bd4oxv8f2hfAFvAJeBIpqWoY8wBk5q+VxAsI8wA+APsKM1+GNA/gBlifM4DfDsM7Aa4iQRHDq3/EaJ92xH8F7Hnk0OpzPib413GTHcCVuEc+UYa8Br4ADz2iJQcgRHyTyQY9Bi1VQKj4pAC0if8FfG7whCRaoEv8NvCo8IW6LUwewGZhuC7Dk5kX8RfFtxhjUgB8xYtoAZEUgBDxyQEIFZ8UgC7xsk3+5uj1JFqgr/gkKmCI+MkDkI3N14a1vTzqmsq+2gmygXrX0BqNWwYLm6EY4j32RO4hYwMYVXznRqFgprUZGl38mABMiB8LQJd4+Qf6sndTB164aA/QFG9+IfQSOGt51A2dedMAtMWbXggtQrxZAG3i74sVXizDM9cCixRvrgIWLd4UgBXgDnjseCTHLvvqLUy1wC7wsfZKTVO8qQooZ6UKQVu8SQCSlEB4D8i7xFhu37TYNdUC1STFE34ELtH7DDcLoI+YPtdkAKm9GAmtglwBuQISezdopgW0D0mFCm0aL+8F3tZ+/AMst93A5y8x7WNysQC44kjuz4cC0D4oqQlAcj8YCkD7qKwWgJ/ABvB9KAC5XvOwtAYAES+HKM+7gvt4QBlDKuGwOC6/FvG4fFeOvr+L4d0WJ1iPu2a+DBoCwDeRSY3LACY1XQrJ5gpQgDqpkLkCJjVdCsnOvgL+Ac3X1EEJkLOsAAAAAElFTkSuQmCC" width="15" alt="Ouvrir le lien"/></a>
			
			</td>
			
		</tr>
		
		<tr>
		
			<td class="align_left" width="20%">
	
				<label for="url">🔗 Ancre du lien :</label></td>
				
			<td>
 
				<input value="<?php echo $s['ancre']; ?>" type="text" name="ancre" style="width : 400px" class="soumettre_input" />
				
			</td>
			
		</tr>
		
		<tr>
		
			<td class="align_left" width="20%">
	
				<label for="url"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAACGUlEQVQ4T53TX0hTURzA8e/d3XbnxLUlpaOkFZW0IQqJkQ8t6GHuodAX3YOi/aGQeqgnrd4iCOrVpIdhovUWIiQsoX9oPkSFPdjIbGrDJ3Pq5pzc/bk3NuWWNkI7T4dzzu9zfr8f5wg/b5a6VUV4jMBBdjZmUWkV5jvs0/8RvH6Vyoww32lXs3PDgWr0ZcdRFn+QCn9EiS9sKx8NMJ++RqHn1oaskpoeIzH6iOTk639CGmAsP4Op2oe+pBxxz2EtKPn1JSvPbqCsRvJCGvDnrljsoKD2EgUnWkA0kFmeI+pvJBOZ/QvRAKmqAaniHOm5ceTxgVyQ3u7C0tKDaCsjsxRmucuLkljahOTvgZJmbcxPfPgeusLdWNufI1r3I38JEHtyMT8g7j2K8YgbyenBcKg2dyj57S3Rvlb0die29iHQiSz7G0mF3mlI3h5IzjqKmroQjGYSI92sBu5S1HAfU00zyclXRHtb8gCCgFRxFjJp5GAAyenF0uyHTIrIg5PoTEXYrr8BJc3CHReqvJJDfjexsh6Lrzu3GOs/jxwcxnplEIOjJpdBNpPijg/orPuI9vhITo1sAaoasDQ93AAuIAdfYHZfpbDuNvLEELGnl9nV1k/2vcQHO1l737cZIFtCZf16CRNDoKroS49hdHrILIaRPw8gubyIJeWkvo+SCn/SgBnAsa2Hv/WQQCj7G08BvTv+kQIhFKHtFxlX1+he0M78AAAAAElFTkSuQmCC"> Flux RSS :</label></td>
				
			<td>
 
				<input value="<?php echo $s['url_rss']; ?>" type="text" name="url_rss" style="width : 400px" class="soumettre_input" />  <?php if (!empty($s['url_rss'])) {?><a href="<?php echo $s['url_rss']; ?>" target="_blank"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEAAAABACAYAAACqaXHeAAACf0lEQVR4Xu2av04VQRSHPyTRWPgAJCa2QKT3HRQLhBYSwhtoCw01vY3RzkKEAnkHekKoKKx8ACtM/JOT7JrNZnZ3ZnfO3bOzc5tb3Nmz5/fNOb+duTtLzPyzNHP9ZAC5AmZOILfAzAsgm2BICzwDjoAXwCrwIFL1uHI4Bd4oxv8f2hfAFvAJeBIpqWoY8wBk5q+VxAsI8wA+APsKM1+GNA/gBlifM4DfDsM7Aa4iQRHDq3/EaJ92xH8F7Hnk0OpzPib413GTHcCVuEc+UYa8Br4ADz2iJQcgRHyTyQY9Bi1VQKj4pAC0if8FfG7whCRaoEv8NvCo8IW6LUwewGZhuC7Dk5kX8RfFtxhjUgB8xYtoAZEUgBDxyQEIFZ8UgC7xsk3+5uj1JFqgr/gkKmCI+MkDkI3N14a1vTzqmsq+2gmygXrX0BqNWwYLm6EY4j32RO4hYwMYVXznRqFgprUZGl38mABMiB8LQJd4+Qf6sndTB164aA/QFG9+IfQSOGt51A2dedMAtMWbXggtQrxZAG3i74sVXizDM9cCixRvrgIWLd4UgBXgDnjseCTHLvvqLUy1wC7wsfZKTVO8qQooZ6UKQVu8SQCSlEB4D8i7xFhu37TYNdUC1STFE34ELtH7DDcLoI+YPtdkAKm9GAmtglwBuQISezdopgW0D0mFCm0aL+8F3tZ+/AMst93A5y8x7WNysQC44kjuz4cC0D4oqQlAcj8YCkD7qKwWgJ/ABvB9KAC5XvOwtAYAES+HKM+7gvt4QBlDKuGwOC6/FvG4fFeOvr+L4d0WJ1iPu2a+DBoCwDeRSY3LACY1XQrJ5gpQgDqpkLkCJjVdCsnOvgL+Ac3X1EEJkLOsAAAAAElFTkSuQmCC" width="15" alt="Ouvrir le lien"/></a><?php } ?>
				
			</td>
			
		</tr>
		<tr>
		
			<td class="align_left" width="20%">
	
				<label for="url">🔗 URL Lien retour :</label></td>
				
			<td>
 
				<input value="<?php echo $s['url_retour']; ?>" type="text" name="url_retour" style="width : 400px" class="soumettre_input" />  <?php if (!empty($s['url_retour'])) {?><a href="<?php echo $s['url_retour']; ?>" target="_blank"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEAAAABACAYAAACqaXHeAAACf0lEQVR4Xu2av04VQRSHPyTRWPgAJCa2QKT3HRQLhBYSwhtoCw01vY3RzkKEAnkHekKoKKx8ACtM/JOT7JrNZnZ3ZnfO3bOzc5tb3Nmz5/fNOb+duTtLzPyzNHP9ZAC5AmZOILfAzAsgm2BICzwDjoAXwCrwIFL1uHI4Bd4oxv8f2hfAFvAJeBIpqWoY8wBk5q+VxAsI8wA+APsKM1+GNA/gBlifM4DfDsM7Aa4iQRHDq3/EaJ92xH8F7Hnk0OpzPib413GTHcCVuEc+UYa8Br4ADz2iJQcgRHyTyQY9Bi1VQKj4pAC0if8FfG7whCRaoEv8NvCo8IW6LUwewGZhuC7Dk5kX8RfFtxhjUgB8xYtoAZEUgBDxyQEIFZ8UgC7xsk3+5uj1JFqgr/gkKmCI+MkDkI3N14a1vTzqmsq+2gmygXrX0BqNWwYLm6EY4j32RO4hYwMYVXznRqFgprUZGl38mABMiB8LQJd4+Qf6sndTB164aA/QFG9+IfQSOGt51A2dedMAtMWbXggtQrxZAG3i74sVXizDM9cCixRvrgIWLd4UgBXgDnjseCTHLvvqLUy1wC7wsfZKTVO8qQooZ6UKQVu8SQCSlEB4D8i7xFhu37TYNdUC1STFE34ELtH7DDcLoI+YPtdkAKm9GAmtglwBuQISezdopgW0D0mFCm0aL+8F3tZ+/AMst93A5y8x7WNysQC44kjuz4cC0D4oqQlAcj8YCkD7qKwWgJ/ABvB9KAC5XvOwtAYAES+HKM+7gvt4QBlDKuGwOC6/FvG4fFeOvr+L4d0WJ1iPu2a+DBoCwDeRSY3LACY1XQrJ5gpQgDqpkLkCJjVdCsnOvgL+Ac3X1EEJkLOsAAAAAElFTkSuQmCC" width="15" alt="Ouvrir le lien"/></a><?php } ?>
				
			</td>
			
		</tr>
		<tr>
		
			<td class="align_left" width="20%">
			
				<label for="url">🗨️ Catégorie :</label></td>
				
			<td>
			
				<select name="sect" style="width: 400px;">
					<?php 
					
					$cat2 = rcp_cat("", "ORDER BY titre ASC");  
					
						foreach($cat2 as $c2){
						
						$sect2 = rcp_sect("", $c2['id_cat'], "ORDER BY titre ASC"); 
						
							foreach($sect2 as $se2){
							?>
					
					<option value="<?php echo $se2['id_sect']; ?>" <?php if ($se2['id_sect'] == $s['sect']) { echo 'selected'; } ?>><?php echo stripslashes($c2['titre']); ?> >> <?php echo stripslashes($se2['titre']); ?></option>
					
							<?php }
							
						} ?>
				</select>
				
			</td>
			
		</tr>
		<tr>
		
			<td class="align_left" width="20%">
			
				<label for="url">⭐ Noter ce site :</label></td>
				
			<td>
			
				 <input type="text" name="note" style="width : 100px" class="soumettre_input" value="<?php echo $s['note']; ?>" /></td>
				
		</tr>
		
		<tr>
		
			<td colspan="2" class="conseltd">
			
				<span class="conseil">⬆️ Important dans le cas où vous avez choisi, lors de l'installation de Janembart, de classer les sites par notation. Dans le cas contraire, ce champ n'aura aucune incidence tant que vous ne changez pas le critère de classement (modifiable depuis config.php). Il est conseillé de donner une note entre 0 et 100.</span>
			</td>
			
		</tr>
		<tr>
		
			<td colspan="2">💬 Description : </td>
			
		</tr>
		<tr>
		
			<td colspan="2"><textarea class="soumettre_textarea " name="description" id="mytextarea"><?php echo convert_br(stripslashes($s['description']), 1); ?></textarea></td>
			
		</tr>
		
		<tr>
		
			<td colspan="2" class="conseltd">
			
				<span class="conseil" style="background:#eee;color:black;">💡 Checkez rapidement si la description est unique en regardant les premiers résultats sur Google. Ce n'est pas la méthode ultime mais ça dépanne ! <a href='https://www.google.com/search?hl=fr&q="<?php echo urlencode(cut_str($s['description'], 111)); ?>"' target="_blank" rel="nofollow noreferrer">👀 Comparer sur Google 👀</span></td>
				
		</tr>
		
		<tr>
		
			<td>Sécurité Anti-duplicate</td>
			
			<td style="text-align:center">
		
				<select name="type">
								
					<option value="1" <?php if ($s['type'] == 1) { ?>selected<?php } ?>>Niveau 1</option>
					<option value="2" <?php if ($s['type'] == 2) { ?>selected<?php } ?>>Niveau 2</option>
					
				</select>
			
			</td>
			
		</tr>
		<tr>
		
			<td colspan="2" class="conseltd">
			
				<span class="conseil">
				
				<?php 
				if ($s['type'] == 1)
					echo '
				Ce site a été jugé apte à avoir une fiche dédiée. Si vous pensez que la description est dupliqué n\'hésitez pas à le passer en niveau 2. ⚠️ Au niveau 2 le backlink se fait directement depuis la section ou le site est répertorié !';
				else 
					echo '
				Ce site n\'a pas été jugé apte à avoir une fiche dédiée. Si vous pensez qu\'il en mérite une passez le au <em>niveau 1</em> ! ⚠️ Au niveau 2 le backlink se fait directement depuis la section ou le site est répertorié !';
				?>
				
				</span></td>
				
		</tr>
		
		<tr>
		
			<td colspan="2"><p style="text-align:center"><input value="<?php echo $s['id_site']; ?>" name="id" type="hidden" style="margin-top: 3px;" /><input value="Valider" type="submit" style="margin-top: 3px;" /></p></td>
			
		</tr>
		</table>
		</fieldset>
		</form>

		
		
		<form method="POST" action="gestion/?act=1&f=31&id=<?php echo $s['id_site']; ?>">
			<fieldset id="supprimer2">
				<table width="100%" class="formulaire" style="margin-top:20px;">
					<tr>
						<td colspan="2">Motif de refus : </td>
					</tr>
					<tr>
						<td colspan="2"><textarea style="width:100%; height:100px" name="motif">Description dupliquée et/ou contenu refusé.</textarea></td>
					</tr>
					<?php if ($s['valide'] == 2) { // Valable lorsque le site n'a pas encoré été accepté/refusé mais pas pour les futures modifications. ?>
					<tr>
						<td colspan="2"><input type="checkbox" name="maraboulet" id="maraboulet"><label for="maraboulet" style="cursor:pointer;">🧙 Suppression Ninja .:. En cochant cette case, vous <strong>supprimerez sans prévenir par email</strong> le site de ce brave marabout, qui mine de rien vous à mis un backlink pas dégueu en footer !</label></td>
					</tr>
					<?php } ?>
					<tr>
						<td colspan="2"><p style="text-align:center"><input value="Supprimer" type="submit" style="margin-top: 3px;" /></p></td>
					</tr>
				</table>
			</fieldset>
		</form>
		
		<?php // Relance Lien Retour // ?>
		<?php if (!empty($s['url_retour']) AND ($s['valide'] == 1)) {?>
		
		<h2>💥 Relance Retrait Lien Retour 💥</h2>
		<form method="POST" action="gestion/?act=1&f=666&id=<?php echo $s['id_site']; ?>">
			<fieldset id="supprimer2">
				<table width="100%" class="formulaire" style="margin-top:20px;">

					<tr>
						<td colspan="2">
						<textarea style="width:100%; height:100px" name="relancebl" id="relancebl">
							<?php 
							$contenu_du_mail_relance = file_get_contents(BATBASE.'/config/mail_retrait_backlink.php');
							$contenu_du_mail_relance = str_replace('%url_annuaire%', $url_annuaire, $contenu_du_mail_relance);
							$contenu_du_mail_relance = str_replace('%urlfichesite%', $s['info']['permanlink'], $contenu_du_mail_relance);
							$contenu_du_mail_relance = str_replace('%url_lien_retour%', $s['url_retour'], $contenu_du_mail_relance);
							echo $contenu_du_mail_relance;	
							?>
						</textarea>
						</td>
					</tr>
					<tr>
						<td colspan="2"><span class="conseil">Le code %BACKLINK_SOUHAITE% sera automatiquement remplacé dans le mail par le backlink et l'ancre saisie ci-dessous.</span></td>
					</tr>
					<tr>
		
						<td class="align_left" width="20%">
				
							<label for="url">🔗 URL Lien retour souhaité</label></td>
							
						<td>		 
							<input value="<?php echo $url_annuaire; ?>" type="text" name="url_retour_souhaitee" style="width : 400px" class="soumettre_input" />  	
						</td>
			
					</tr>
					<tr>
		
						<td class="align_left" width="20%">
				
							<label for="url">⚓ Ancre du Lien retour souhaité</label></td>
							
						<td>		 
							<input value="<?php echo $titre_annuaire; ?>" type="text" name="ancre_retour_souhaitee" style="width : 400px" class="soumettre_input" />  	
						</td>
			
					</tr>
		
					<tr>
						<td colspan="2"><p style="text-align:center"><input value="Envoyer" type="submit" style="margin-top: 3px;" /></p></td>
					</tr>
				</table>
			</fieldset>
		</form>
		
		<?php } else { ?>
		
		<h2>💥 Pousser la mise en place d'un lien Retour 💥</h2>
		<form method="POST" action="gestion/?act=1&f=667&id=<?php echo $s['id_site']; ?>">
			<fieldset id="supprimer2">
				<table width="100%" class="formulaire" style="margin-top:20px;">

					<tr>
						<td colspan="2">
						<textarea style="width:100%; height:100px" name="relancebl" id="relancebl">
							<?php 
							$contenu_du_mail_relance = file_get_contents(BATBASE.'/config/mail_demande_backlink.php');
							$contenu_du_mail_relance = str_replace('%url_annuaire%', $url_annuaire, $contenu_du_mail_relance);
							$contenu_du_mail_relance = str_replace('%urlfichesite%', $s['info']['permanlink'], $contenu_du_mail_relance);
							echo $contenu_du_mail_relance;	
							?>
						</textarea>
						</td>
					</tr>
					<tr>
						<td colspan="2"><span class="conseil">Le code %BACKLINK_SOUHAITE% sera automatiquement remplacé dans le mail par le backlink et l'ancre saisie ci-dessous.</span></td>
					</tr>
					<tr>
		
						<td class="align_left" width="20%">
				
							<label for="url">🔗 URL Lien retour souhaité</label></td>
							
						<td>		 
							<input value="<?php echo $url_annuaire; ?>" type="text" name="url_retour_souhaitee" style="width : 400px" class="soumettre_input" />  	
						</td>
			
					</tr>
					<tr>
		
						<td class="align_left" width="20%">
				
							<label for="url">⚓ Ancre du Lien retour souhaité</label></td>
							
						<td>		 
							<input value="<?php echo $titre_annuaire; ?>" type="text" name="ancre_retour_souhaitee" style="width : 400px" class="soumettre_input" />  	
						</td>
			
					</tr>
		
					<tr>
						<td colspan="2"><p style="text-align:center"><input value="Envoyer" type="submit" style="margin-top: 3px;" /></p></td>
					</tr>
				</table>
			</fieldset>
		</form>
		

		<?php } ?>



<?php
					}
			
			}
				
	}
		
}