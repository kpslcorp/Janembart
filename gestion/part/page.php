<?php
$aiguilleur = isset($_GET['f']) ? $_GET['f'] : NULL;
if($aiguilleur=='1') { 

	$cool_id = isset($_GET['id']) ? $_GET['id'] : NULL;
	if (isset($cool_id)){
	
		$p = rcp_page(intval($cool_id), "");
		
	?>
	<form method="POST" action="gestion/?act=9&f=12">
	
	<?php 
	
	} else { ?>
	
	<form method="POST" action="gestion/?act=9&f=11"> 
	
	<?php }

	$cool_titre = stripslashes($p[0]['titre']);
	$cool_contenu = stripslashes($p[0]['contenu']);

	?>
	
	<fieldset id="ajouter2">
	
	<table width="100%" class="formulaire">
	
		<tr>
			<td colspan="2">
				<input <?php if (isset($cool_titre)) {echo "value='$cool_titre'";} ?> type="text" name="titre" style="width : 400px" class="soumettre_input" placeholder="Titre :"/>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<textarea class="soumettre_textarea" name="contenu" id="mytextarea"><?php if (isset($cool_contenu)) {echo $cool_contenu;} ?></textarea>
			</td>
		</tr>
		<tr>
			<td colspan="2" class="conseltd">
				<span class="conseil">Les sauts de ligne seront automatiquement convertit. Il est nécessaire d'utiliser les balises html si vous souhaitez faire un lien ou mettre en évidence un morceau de votre texte.</span>
			</td>
		</tr>
		<tr>
			<td colspan="2" style="text-align:center">
				<input value="<?php echo intval($cool_id); ?>" name="id" type="hidden" style="margin-top: 3px;" />
				<input value="Valider" type="submit" style="margin-top: 3px;" />
			</td>
		</tr>
		
	</table>
	
	</fieldset>
	
</form>
<?php
}


elseif($aiguilleur=='11'){
	
	$msg_erreur = "Grosse erreur, un ou plusieurs des champs doivent être vide :<br/><br/>";
	$message_retour = '<br /><br /><p class="recommencer"><a href="javascript:history.go(-1)">Retourner sur le formulaire</a></p>';
	$message = $msg_erreur;
	if (empty($_POST['titre']))
	$message .= '<font color="red">- Le titre</font><br/>';
	if (empty($_POST['contenu']))
	$message .= '<font color="red">- La page est vide ?</font><br/>';

if (strlen($message) > strlen($msg_erreur)) {
	echo $message;
	echo $message_retour;	
} else {
	foreach($_POST as $index => $valeur) {$$index = mysqli_real_escape_string($connexion,trim($valeur));}

	$description = str_replace("\n", "<br />", $description);
	
	$sql = "INSERT INTO ".TABLE_PAGE." VALUES ( '',  '".$titre."',  '".$contenu."',  '".$compteur."')";
	
	$res = mysqli_query($connexion,$sql);
		  
		if ($res) {
			echo '<p style="padding;10px;text-align:center">Page ajouté avec succès.</p>';
			echo '<p style="padding;10px;text-align:center"><a href="gestion/?act=9">Retourner gérer les pages.</a></p>';
		} else {
			echo mysqli_error();
		}
	}
}
elseif($aiguilleur=='12'){

		foreach($_POST as $index => $valeur) {
			$$index = mysqli_real_escape_string($connexion,trim($valeur));
		}
		  
		$sql = "UPDATE ".TABLE_PAGE." SET titre='".$titre."', contenu='".$contenu."' WHERE id_page = '".$id."'";
		  
		$res = mysqli_query($connexion,$sql);
		  
		  if ($res) {
		  ?>
			<p class="align_center">🔄 Page mise à jour avec succès.</p>
			<p class="align_center"><a href="gestion/?act=9">Retourner gérer les pages.</a></p>
		  <?php
		  }
		  
		  else {
			echo mysqli_error();
		  }
}
elseif($aiguilleur=='2'){
?>
<form method="POST" action="gestion/?act=9&f=21">
<p class="align_center">⚠️ Etes-vous sur de vouloir supprimer définitivement cette page ?</p>
<table width="100%">
	<tr>
		<td style="text-align:center"><input type="radio" name="ok" id="oui" />❌ Oui</td>
	</tr>
	<tr>
		<td><input value="<?php echo $_GET['id']; ?>" name="id" type="hidden" style="margin-top: 3px;" /><input value="Valider" type="submit" style="margin-top: 3px;" /></td>
	</tr>
</table>
</form>
<?php
}

elseif($aiguilleur=='21'){
		
		$res = mysqli_query($connexion,"DELETE FROM ".TABLE_PAGE." WHERE id_page ='".$_POST['id']."'"); 

		if ($res) {
?>
<p class="align_center">❌ Page supprimée avec succès.</p>
<p class="align_center"><a href="gestion/?act=9">Retourner gérer les pages.</a></p>
<?php

	} else {
		echo mysqli_error();
	}
}

else {

	$page_index = '
	<p>Le système de page Janembart X Bartemis n\'est pas un CMS de blog mais il permet tout de même de créer des pages, notamment pour tout ce qui est essentiel à votre projet : mentions légales, politique de confidentialité...</p>
	
	<p style="text-align:center;"><a href="gestion/?act=9&f=1">🏹 Créer une nouvelle Page</a></p>
	
	<div>
	
		<div class="titre_colonne" style="margin-top:5px">Page</div>
		
		<table width="100%" id="listisite">
			<thead>
			<tr>
			
				<th>Page <a href="gestion/?act=9&order=titre-ASC">&uArr;</a> <a href="gestion/?act=9&order=titre-DESC">&dArr;</a></th>
				<th style="text-align:center">ID <a href="gestion/?act=9&order=id_page-ASC">&uArr;</a> <a href="gestion/?act=9&order=id_page-DESC">&dArr;</a></th>
				<th align="center" style="text-align:center">X</th>
				
			</tr></thead><tbody>';
				if(!empty($_GET['order']))
					$get_order = str_replace('-', ' ', strip_tags($_GET['order']));
				else
					$get_order = 'id_page DESC';
					
				 $page = rcp_page('', 'ORDER BY '.$get_order);
				 
				 foreach ($page as $p) { 
				 
			$page_index.='<tr>
			
				<td>⚙️ <a href="gestion/?act=9&f=1&id='.$p['id_page'].'">'.stripslashes($p['titre']).'</a></td>
				<td style="text-align:center">'.$p['id_page'].'</td>
				<td style="text-align:center"><a href="gestion/?act=9&f=2&id='.$p['id_page'].'"><img src="images/supprimer.png" border="0"></a></td>
				
			</tr>';
			} 
		$page_index.='
		</tbody></table>
	</div>';
	
	echo $page_index;

}