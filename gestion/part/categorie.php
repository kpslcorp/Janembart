<?php
$aiguilleur = isset($_GET['f']) ? $_GET['f'] : NULL;
$id = isset($_GET['id']) ? $_GET['id'] : NULL;
$id = valid_donnees($id);

if($aiguilleur=='1'){ // Formulaire Catégorie

	if (isset($_GET['id'])){
	
	$c = rcp_cat($_GET['id'], '');
	
	$cool_cat_titre = stripslashes($c[0]['titre']);
	$cool_cat_contenu = stripslashes($c[0]['description']);
	
	// Cas 1 = Update
	
	?>
	
<form method="POST" action="gestion/?act=8&f=12">
		
	<?php } else { // Cas 2 = Création ?>
	
<form method="POST" action="gestion/?act=8&f=11"> 
	
	<?php } ?>

	<fieldset id="ajouter2">
	
		<p>Nom de la catégorie ⬇️</p>
				
		<p><input <?php if (isset($cool_cat_titre)) {echo "value='$cool_cat_titre'";} ?> type="text" name="titre" class="soumettre_input" placeholder="Titre de la catégorie" /></p>
		
		<p>Description de la catégorie ⬇️</p>

		<p><textarea class="soumettre_textarea" name="description" placeholder="Description de la catégorie" id="mytextarea"><?php if (isset($cool_cat_contenu)) {echo $cool_cat_contenu;} ?></textarea></p>
		
		<input value="<?php echo intval($id); ?>" name="id" type="hidden" />
		
		<p><input value="Valider" type="submit" /></p>
	
	</fieldset>
	
</form>
<?php
	
}

elseif($aiguilleur=='11'){ // Création Catégorie

	$msg_erreur = "<p>⚠️ [ERREUR] Un ou plusieurs des champs du formulaire n'ont pas été complétés :</p>";
	$message_retour = '<p class="recommencer"><a href="javascript:history.go(-1)">⬅️ Retourner sur le formulaire</a></p>';
	$message = $msg_erreur;
	
	if (empty($_POST['titre'])) {
		$message .= '<p><font color="red">- Merci de renseigner le titre de la catégorie</font></p>';
	}
	
	if (empty($_POST['description'])) {
		$message .= '<p><font color="red">- Merci de renseigner la description de la catégorie</font></p>';
	}

	if (strlen($message) > strlen($msg_erreur)) {

		echo $message;
		echo $message_retour;
		
	} 
	
	else {

	foreach($_POST as $index => $valeur) {$$index = mysqli_real_escape_string($connexion,trim($valeur));}

	$description = str_replace("\n", "<br />", $description);
	$description = strip_tags($description);
	
	$sql = "INSERT INTO ".TABLE_CAT." (titre,description) VALUES ('".$titre."', '".$description."')";
	 
	$res = mysqli_query($connexion,$sql);
		  
	if ($res) {
		
		$retour = mysqli_query($connexion,'SELECT * from '.TABLE_CAT.' ORDER BY id_cat desc LIMIT 1');
		$donnees = mysqli_fetch_array($retour);
		
		if ($donnees) 
		$id_cat = $donnees['id_cat'];
				
		$description = mysqli_real_escape_string($connexion,'Retrouvez dans cette rubrique tous les sites de la catégorie '.$titre.' qui n\'ont pas trouvés leur place dans les autres sections.');
		
			$sql = "INSERT INTO ".TABLE_SECT." VALUES ('', 'Autres', '".$id_cat."',  '".$description."',  '2')";
		  
			$res = mysqli_query($connexion,$sql);
		
			if ($res) {
			
				echo '<p style="padding;10px;text-align:center">Catégorie ajoutée avec succès</p>';
				echo '<p style="padding;10px;text-align:center">Une section <b>Autres</b> a aussi été créée.</p>';
				echo '<p style="padding;10px;text-align:center"><a href="gestion/?act=8">Retourner gérer les catégories.</a></p>';
			}
			else {
				echo mysqli_error($connexion);
			}
		}
		else {
			echo mysqli_error($connexion);
		}
	}

}
elseif($aiguilleur=='12'){ // Update Catégorie


		foreach($_POST as $index => $valeur) {
			$$index = mysqli_real_escape_string($connexion,trim($valeur));
		  }
		  
		  $sql = "UPDATE ".TABLE_CAT." SET titre='".$titre."', description='".$description."'WHERE id_cat = '".$_POST['id']."'";
		  
		  $res = mysqli_query($connexion,$sql);
		  
		  if ($res) {
		  ?>
			<p class="align_center">Catégorie ajoutée avec succès.</p>
			<p class="align_center"><a href="gestion/?act=8">Retourner gérer les catégories.</a></p>
		  <?php
		  }
		  
		  else {
			echo mysqli_error($connexion);
		  }
}

elseif($aiguilleur=='47'){ // Supression Catégorie
		
		$res = mysqli_query($connexion,"DELETE FROM ".TABLE_CAT." WHERE id_cat ='".$_GET['id']."'"); 
		  
		  if ($res) {
			
?>
<p class="align_center">Catégorie supprimée avec succès !</p>
<p class="align_center"><a href="gestion/?act=8">Retourner gérer les catégories.</a></p>
<?php

		  } else {
			echo mysqli_error($connexion);
		  }
}

elseif($aiguilleur=='46'){ // Supression Section
		
		$res = mysqli_query($connexion,"DELETE FROM ".TABLE_SECT." WHERE id_sect ='".$_GET['id']."'"); 
		  
		  if ($res) {
			
?>
<p class="align_center">Section supprimée avec succès.</p>
<p class="align_center"><a href="gestion/?act=8">Retourner gérer les sections.</a></p>
<?php

		  } else {
			echo mysqli_error($connexion);
		  }
}


elseif($aiguilleur=='2'){ // Formulaire Section

	if (isset($_GET['id'])){
	
	$se = rcp_sect(intval($_GET['id']), "", "");
	
	$cool_se_titre = stripslashes($se[0]['titre']);
	$cool_se_contenu = stripslashes($se[0]['description']);
	
	// Update Section
	?>
	
<form method="POST" action="gestion/?act=8&f=22">
	
	<?php } else { 	// Création Section	?>
	
<form method="POST" action="gestion/?act=8&f=21">

	<?php } ?>

	<fieldset id="ajouter2">
	
	<p>Catégorie Parente ⬇️</p>
	<p>
		<select name="id_cat" style="width: 400px;">
			<?php $categorie = rcp_cat('', 'ORDER BY titre ASC');
					foreach($categorie as $cat) { ?>	
						<option value="<?php echo $cat['id_cat']; ?>" <?php if ($se[0]['id_cat'] == $cat['id_cat']) { echo 'selected'; } ?>><?php echo stripslashes($cat['titre']); ?></option>
			<?php } ?>
		</select>
	</p>
	<p>Nom de la section (sous-catégorie) ⬇️</p>
	<p><input <?php if (isset($cool_se_titre)) {echo "value='$cool_se_titre'";} ?> type="text" name="titre" class="soumettre_input" placeholder="Titre de la section" /></p>
	
	<p>Présentation de la section ⬇️</p>
	<p><textarea class="soumettre_textarea" name="description" placeholder="Présentation de la section" id="mytextarea"><?php if (isset($cool_se_contenu)) {echo $cool_se_contenu;} ?></textarea></p>
	
	<input value="<?php echo intval($id); ?>" name="id" type="hidden" />
	
	<p><input value="Valider" type="submit" /></p>
	
	</fieldset>
	
</form>
<?php
}
elseif($aiguilleur=='21'){ // Création Section

	$msg_erreur = "<p>⚠️ [ERREUR] Un ou plusieurs des champs du formulaire n'ont pas été complétés :</p>";
	$message_retour = '<p class="recommencer"><a href="javascript:history.go(-1)">🔙 Retourner sur le formulaire</a></p>';
	$message = $msg_erreur;
	if (empty($_POST['titre']))
	$message .= '<p><font color="red">- Le titre</font></p>';
	if (empty($_POST['description']))
	$message .= '<p><font color="red">- La description</font></p>';
	if (empty($_POST['id_cat']))
	$message .= '<p><font color="red">- La catégorie parente</font></p>';

if (strlen($message) > strlen($msg_erreur)) {

	echo $message;
	echo $message_retour;
	
} else {

	foreach($_POST as $index => $valeur) {$$index = mysqli_real_escape_string($connexion,trim($valeur));}

	$description = str_replace("\n", "<br />", $description);
	
	$sql = "INSERT INTO ".TABLE_SECT." VALUES ('', '".$titre."', '".$id_cat."', '".$description."','2')";
	
	$res = mysqli_query($connexion,$sql);
		  
		if ($res) {
			echo '<p style="padding;10px;text-align:center">Section ajoutée avec succès.</p>';
			echo '<p style="padding;10px;text-align:center"><a href="gestion/?act=8">Retourner gérer les sections.</a></p>';
		} else {
			echo mysqli_error($connexion);
		}
	}

}
elseif($aiguilleur=='22'){ // Update Section

		foreach($_POST as $index => $valeur) {$$index = mysqli_real_escape_string($connexion,trim($valeur));}
		  
		$sql = "UPDATE ".TABLE_SECT." SET titre='".$titre."', id_cat='".$id_cat."', description='".$description."' WHERE id_sect = '".$_POST['id']."'";

		$res = mysqli_query($connexion,$sql);
		  
		  if ($res) {
		  ?>
			<p class="align_center">Section mise à jour avec succès.</p>
			<p class="align_center"><a href="gestion/?act=8">Retourner gérer les catégories.</a></p>
		  <?php
		  }
		  
		  else {
			echo mysqli_error($connexion);
		  }
} else { // Accueil Admin Catégorie / Section ?>

	<table class="tablecat catousect">
		<tr>
			<td><a href="gestion/?act=8&f=1">🆕 Créer une Catégorie</td>
			<td><a href="gestion/?act=8&f=2">🆕 Créer une Section</td>
		</tr>
	</table>
			
	<table class="tablecat">
		<thead>
			<tr>
				<th width="80%">⚙️ Paramétrer les sections</th>
				<th>ID</th>
				<th>X</th>
			</tr>
		</thead>
		<tbody>
		<?php 
		
		$cat = rcp_cat("", "ORDER BY titre ASC");
		if (!empty($cat)) {
			
			foreach($cat as $c){
			
				$sect = rcp_sect("", $c['id_cat'], "ORDER BY titre ASC");
						
					foreach($sect as $se){

		?>
		
		<tr>
			<td width="80%" class="align_left secsec">⚙️ <a href="gestion/?act=8&f=2&id=<?php echo $se['id_sect']; ?>"><?php echo stripslashes($c['titre']); ?> >> <strong><?php echo stripslashes($se['titre']); ?></strong></a></td>
			<td><?php echo $se['id_sect']; ?></td>
			<td><a href="gestion/?act=8&f=46&id=<?php echo $se['id_sect']; ?>" onclick="return confirm('Attention, si vous supprimez cette rubrique, les sites répertoriés dedans seront orphelins et ne pourront plus être retrouvé !')"><img src="images/supprimer.png" border="0"></a></td>
		</tr>
		
		<?php 							}
					
			} 
			
		}
		
		?>
		</tbody>
	</table>


	<table class="tablecat">
		<thead>
			<tr>
				<th width="80%">⚙️ Paramétrer les catégories</th>
				<th>ID</th>
				<th>X</th>
			</tr>
		</thead>
		<tbody>
		<?php 
		
		$cat = rcp_cat("", "ORDER BY titre ASC");
		
		if (!empty($cat)) {
			
			foreach($cat as $c){
				
		?>
		
		<tr>
		
			<td width="80%" class="align_left">⚙️ <a href="gestion/?act=8&f=1&id=<?php echo $c['id_cat']; ?>"><?php echo stripslashes($c['titre']).' ('.stat_section($c['id_cat']); ?>*)</a></td>
			<td><?php echo $c['id_cat']; ?></td>
			<td><a href="gestion/?act=8&f=47&id=<?php echo $c['id_cat']; ?>" onclick="return confirm('Attention, si vous supprimez cette rubrique, les sites répertoriés dedans seront orphelins et ne pourront plus être retrouvé !')"><img src="images/supprimer.png" border="0"></a></td>
			
		</tr>
		
		<?php } 
		
		}
		
		?>
		</tbody>
	</table>
	<p>* Correspond au nombre de sections dans la catégorie</p>
	
<?php
}
