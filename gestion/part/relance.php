<?php
$aiguilleur = isset($_GET['f']) ? $_GET['f'] : NULL;
if($aiguilleur=='1'){

?>

<form method="POST" action="gestion/?act=67&f=11">
<p class="align_center">🔻 Etes-vous sur de vouloir downgrader ce site et en avertir son webmaster ?</p>
<table width="100%">
	<tr>
		<td style="text-align:center"><input type="radio" name="ok" id="oui" />Oui</td>
	</tr>
	<tr>
		<td>
			<input value="<?php echo $_GET['id']; ?>" name="id_a_retrograder" type="hidden" />
			<input value="<?php echo $_GET['ticket']; ?>" name="id_ticket" type="hidden" />
			<input value="Valider" type="submit" style="margin-top: 3px;" />
		</td>
	</tr>
</table>
</form>

<?php
}

elseif($aiguilleur=='11'){
	
		$id_a_retrograder = valid_donnees($_POST['id_a_retrograder']);
		$id_ticket = valid_donnees($_POST['id_ticket']); 
		
		$res = mysqli_query($connexion,"UPDATE ".TABLE_SITE." SET note='10', url_retour='' WHERE id_site = '".$id_a_retrograder."'");
		
		
		$site = rcp_site($id_a_retrograder, "", "", "", "", "");
		foreach($site as $s){
			$destinataire = $s['mail_auteur'];
			$site_en_question = $s['url'];
		}
	
		$sujet = "🔻 Re : [URGENT] Suppression de tous vos avantages sur $titre_annuaire 🔻";
		$contenu_du_mail_relance = file_get_contents(BATBASE.'/config/mail_downgrade.php');
		
		$generation_code_souhaite = "&lt;a href='$url_annuaire'>$titre_annuaire&lt;/a>";
		
		$contenu_du_mail_relance = str_replace('%BACKLINK_SOUHAITE%', $generation_code_souhaite, $contenu_du_mail_relance);
		$contenu_du_mail_relance = str_replace('%url_annuaire%', $url_annuaire, $contenu_du_mail_relance);
		$contenu_du_mail_relance = str_replace('%url%', $site_en_question, $contenu_du_mail_relance);
		
		$headers = 'Mime-Version: 1.0'."\r\n";
		$headers .= 'Content-type: text/html; charset=utf-8'."\r\n";
		$headers .= "From: $titre_annuaire <$mail>"."\r\n";
		$headers .= "Reply-To: $titre_annuaire <$mail>"."\r\n";
		
		mail($destinataire, $sujet, $contenu_du_mail_relance, $headers);

		if ($res) {
?>
<p class="align_center">🔻 Site downgradé avec succès + Mail Envoyé</p>
<p class="align_center"><a href="gestion/?act=67&f=2&id=<?php echo $id_ticket; ?>">❌ Supprimer le ticket</a></p>
<p class="align_center"><a href="gestion/?act=67">🎫 Retourner gérer les tickets</a></p>
<p class="align_center"><a href="gestion/?act=1&id=<?php echo $id_a_retrograder; ?>">⚙️ Voir la fiche du site (coté admin)</a></p>

<?php

	} else {
		echo mysqli_error();
	}
}


elseif($aiguilleur=='2'){

?>

<form method="POST" action="gestion/?act=67&f=21">
<p class="align_center">❌ Etes-vous sur de vouloir supprimer définitivement ce ticket ?</p>
<table width="100%">
	<tr>
		<td style="text-align:center"><input type="radio" name="ok" id="oui" />Oui</td>
	</tr>
	<tr>
		<td>
			<input value="<?php echo $_GET['id']; ?>" name="id_ticket" type="hidden" />
			<input value="Valider" type="submit" style="margin-top: 3px;" />
		</td>
	</tr>
</table>
</form>

<?php
}

elseif($aiguilleur=='21'){
		
		$res = mysqli_query($connexion,"DELETE FROM ".TABLE_RELANCE." WHERE id_ticket ='".$_POST['id_ticket']."'"); 

		if ($res) {
?>
<p class="align_center">💥 Ticket supprimé avec succès.</p>
<p class="align_center"><a href="gestion/?act=67">🎫 Retourner gérer les tickets.</a></p>

<?php

	} else {
		echo mysqli_error();
	}
}

else {

	$page_index = '
	<p class="align_center">Voici la liste des sites ayant été relancés pour défaut de lien retour :</p>
	
	<div>
	
		<div class="titre_colonne" style="margin-top:5px">💥 Liste des relances 💥</div>
		
		<table width="100%" id="listisite">
			<thead>
			<tr>
				<th style="text-align:center">ID</th>
				<th style="text-align:center">🌍 Site </th>
				<th style="text-align:center">️🔗</th>
				<th style="text-align:center">️📅 Date</th>
				<th style="text-align:center">Downgrader</th>
				<th style="text-align:center">Clore Ticket</th>
				
			</tr></thead><tbody>';
				
		$mesrelances = mysqli_query($connexion, "SELECT * FROM ".TABLE_RELANCE." n INNER JOIN ".TABLE_SITE." f ON f.id_site = n.id_site ORDER BY id_ticket");
					 
			foreach ($mesrelances as $mcp) { 
			
			$date_ouverture_ticket = date_create($mcp['date_ticket']);
			$date_jour = date_create('now');
			$interval = date_diff($date_ouverture_ticket, $date_jour);
			$belledate = date_format($date_ouverture_ticket, 'd-m-Y');
			$tictacboom = $interval->format('%R%aJ');
			$tictacboom_estim = $interval->format('%a');
			if ($tictacboom_estim > 14) {$alert_miami='red';} else {$alert_miami='black';}
				 
			$page_index.='<tr>
				<td style="text-align:center">'.$mcp['id_ticket'].'</td>
				<td style="text-align:center"><a href="gestion/?act=1&id='. $mcp['id_site']. '">⚙️ '. $mcp['url']. '</a> </td>
				<td style="text-align:center"><a href="'. $mcp['url_retour']. '" target="_blank" rel="nofollow noreferrer">🔗</td>
				<td style="text-align:center"><span style="color:'. $alert_miami. '">'. $belledate. ' ('. $tictacboom .')</span></td>
				<td style="text-align:center"><a href="gestion/?act=67&f=1&id='.$mcp['id_site'].'&ticket='.$mcp['id_ticket'].'">🔻</a></td>
				<td style="text-align:center"><a href="gestion/?act=67&f=2&id='.$mcp['id_ticket'].'">❌</a></td>	
				</tr>';
			
			} 
		$page_index.='
		</tbody></table>
	</div>';
	
	echo $page_index;

}