<?php
$aiguilleur = isset($_GET['f']) ? $_GET['f'] : NULL;
if($aiguilleur=='1') { 

	$id = valid_donnees($_GET['id']);
	$mescomptespros = mysqli_query($connexion, "SELECT * FROM ".TABLE_USER." WHERE id_user = '$id'");
					 
	foreach ($mescomptespros as $mcp) {
		$mail = $mcp['mail'];
		$credit = $mcp['credit'];
		$valide = $mcp['valide'];
	}
	
	if ($valide == 1) { ?>
	
	<form method="POST" action="gestion/?act=99&f=12">
	
	<fieldset id="ajouter2">
	
	<table width="100%" class="formulaire">
	
		<h2><span style="color:orange;">Compte PRO :</span> <?php echo $mail; ?></h2>
		<p style='text-align:center;'>✅ Ce compte pro a bien validé son adresse email.</p>
		<p style='text-align:center;'><a href="mailto:<?php echo $mail; ?>">📧 Cliquez ici pour contacter ce webmaster</a></p>
		
		<h2>🔋 Nombre de crédits restants :</h2>
		<tr>
			<td colspan="2">
				
				<p class="align_center"><input value="<?php echo intval($credit); ?>" name="credit" /></p>
				<input value="<?php echo intval($id); ?>" name="id_user" type="hidden" />
				<input value="<?php echo $mail; ?>" name="mail_user" type="hidden" />
			</td>
		</tr>
		
		<tr>
			<td colspan="2" style="text-align:center">
				<input value="💥 Upgrader" type="submit" style="margin-top: 3px;" />
			</td>
		</tr>
	
	</table>
	
	</fieldset>
	
	</form>

	<?php 
	
	if (!empty($_GET['order'])) {
		
		$lorder = valid_donnees($_GET['order']);
		if ($lorder == "url") {$si_tu_trie_ta_tout_compris = "url";}
		elseif ($lorder == "date") {$si_tu_trie_ta_tout_compris = "date";}
		else {$si_tu_trie_ta_tout_compris = NULL;}
		
	}
	
	$mywebsites = rcp_pro_websites($mail,$si_tu_trie_ta_tout_compris);
	
	if (!empty($mywebsites)) { ?>
		<h2>Sites enregistrés par <?php echo $mail; ?></h2>
		
		<p style='text-align:center;'><a href="gestion/?act=99&f=1&id=<?php echo $id; ?>">🅰️ Tri par Nom</a> | <a href="gestion/?act=99&f=1&id=<?php echo $id; ?>&order=url">🔗 Tri par URL</a> | <a href="gestion/?act=99&f=1&id=<?php echo $id; ?>&order=date">📆 Tri par date de validation</a></p>
		
		<hr />
		
		<div class="textostat">
							<ol>
								<?php
								
								foreach($mywebsites as $m_s){
									$monjolititre 	= $m_s['titre'];
									$majolieid 		= $m_s['id_site'];
									$hk_plink 		= $m_s['info']['permanlink'];
									$url_plink		= $m_s['info']['url'];
									$date2val		= new DateTime($m_s['date2validation']);
									$date2val 		= $date2val->format('d/m/Y');
									
									if ($lorder == "url") { // Hack si on veut trier par URL
										$monjolititre = $url_plink;
									}
								?>	
									<li>
										<span style="display:block;">
											<?php if (isset($hk_plink)) {echo "<a href='$hk_plink' target='_blank' rel='nofollow noopener noreferrer'>🗨️</a>";}; ?>
											<?php if (isset($url_plink)) {echo "<a href='$url_plink' target='_blank' rel='nofollow noopener noreferrer'>🌐</a>";} ?>
											<?php echo "<a href='gestion/?act=1&id=$majolieid'>⚙️ $monjolititre</a> | 📅 $date2val"; ?>
										</span>
									</li>
								
								<?php } ?>
							</ol>
		</div>
	
	<?php } ?>	

	<?php } else { ?>
	<h2><span style="color:orange;">Compte PRO :</span> <?php echo $mail; ?></h2>
	<p>❌ Attention, ce compte pro n'a pas encore validé son email et est donc gelé jusqu'à validation.</p>
	
	<h2>👑 Privilèges Admin 👑</h2>
	<form method="POST" action="gestion/?act=99&f=31">
	<p class="align_center">Voulez-vous activer manuellement ce membre ?</p>
	<table width="100%">
		<tr>
			<td style="text-align:center"><input type="radio" name="ok" id="oui" />Oui</td>
		</tr>
		<tr>
			<td>
				<input value="<?php echo $_GET['id']; ?>" name="id" type="hidden" />
				<input value="Valider" type="submit" style="margin-top: 3px;" />
			</td>
		</tr>
	</table>
	</form>
<?php

	}

}

elseif($aiguilleur=='12'){

		$postcredit = $_POST['credit'];
		$postid = $_POST['id_user'];
		$email_user = $_POST['mail_user'];
		
		$sql = "UPDATE ".TABLE_USER." SET credit='$postcredit' WHERE id_user = '$postid'";
		  
		$res = mysqli_query($connexion,$sql);
		  
		if ($res) { 
			echo '<p class="align_center">🔥 Compte PRO mis à jour avec succès.</p><p class="align_center"><a href="gestion/?act=99">< Retour à la liste des comptes pros</a></p>';
			
			
			$destinataire = $email_user;
			$sujet = "😃 Une mise à jour de vos crédits vient d'être effectuée sur $titre_annuaire !";
			$headers = 'Mime-Version: 1.0'."\r\n";
			$headers .= 'Content-type: text/html; charset=utf-8'."\r\n";
			$headers .= "From: $titre_annuaire <$mail>"."\r\n";
			
			$lagestion = $url_annuaire."acces-pro";

			$message = "<h1>😃 Bonne nouvelle 😃</h1><p>Une mise à jour de vos crédits vient d'être effectuée sur votre compte pro $titre_annuaire !</p><p>💰 RAPPEL : Vous pouvez vous connecter à votre espace PREMIUM à cette adresse <a href='$lagestion'>$lagestion</a></p><p>Bonne journée et à très vite sur $titre_annuaire !</p>";
			
			mail($destinataire, $sujet, $message, $headers);
			
		}
		else {
			echo mysqli_error($connexion);
		}
}

elseif($aiguilleur=='2'){

?>

<form method="POST" action="gestion/?act=99&f=21">
<p class="align_center">❌ Etes-vous sur de vouloir supprimer définitivement ce compte pro ?</p>
<table width="100%">
	<tr>
		<td style="text-align:center"><input type="radio" name="ok" id="oui" />Oui</td>
	</tr>
	<tr>
		<td>
			<input value="<?php echo $_GET['id']; ?>" name="id" type="hidden" />
			<input value="Valider" type="submit" style="margin-top: 3px;" />
		</td>
	</tr>
</table>
</form>

<?php
}

elseif($aiguilleur=='21'){
		
		$res = mysqli_query($connexion,"DELETE FROM ".TABLE_USER." WHERE id_user ='".$_POST['id']."'"); 

		if ($res) {
?>
<p class="align_center">💥 Compte pro supprimé avec succès.</p>
<p class="align_center"><a href="gestion/?act=99">Retourner gérer les comptes pro.</a></p>

<?php

	} else {
		echo mysqli_error($connexion);
	}
}

elseif($aiguilleur=='31'){
		$postid = $_POST['id'];
		$sql = "UPDATE ".TABLE_USER." SET valide=1 WHERE id_user = '$postid'";
		$res = mysqli_query($connexion,$sql); 
		if ($res) {
?>
<p class="align_center">🔓 Compte pro déverouillé avec succès.</p>
<p class="align_center"><a href="gestion/?act=99">Retourner gérer les comptes pro.</a></p>

<?php

	} else {
		echo mysqli_error($connexion);
	}
}

else {

	$page_index = '
	<p>🤑 Vous souhaitez monétiser votre annuaire ?!</p><ol><li>Proposez des comptes pros payants permettant la validation automatique,</li><li>Invitez les intéressés à vous contacter pour en bénéficier puis à s\'inscrire via <a href="gestion/creation-pro.php">cette page</a></li><li>Attribuez ci-dessous des unités à ces derniers sachant que 1 unité = 1 site validé.</li></ol><p>💥 A vous de définir votre tarification, vos moyens de paiement (Paypal/Stripe ou autre), votre facturation et vos avantages supplémentaires pour les membres...</p><p>💡 PS : Une fois le compte pro créé, chaque bénéficiaire est invité à se connecter via cette page <a href="gestion/login.php">cette page</a>. Un encart supplémentaire lui sera rajouté en haut de sidebar.</p>
	
	<div>
	
		<div class="titre_colonne" style="margin-top:5px">💥 Administer mes comptes pro</div>
		
		<table width="100%" id="listisite">
			<thead>
			<tr>
				<th style="text-align:center">☑️</th>
				<th style="text-align:center">ID</th>
				<th style="text-align:center">📧 Mail</th>
				<th style="text-align:center">💰 Unités</th>
				<th style="text-align:center">️🔋 Administrer</th>
				<th style="text-align:center">🗑</th>
				
			</tr></thead><tbody>';
				
		$mescomptespros = mysqli_query($connexion, "SELECT * FROM ".TABLE_USER." WHERE statut='pr0'");
					 
			foreach ($mescomptespros as $mcp) { 
			
			if ($mcp['valide'] == 1) {$vornot="✅";} else {$vornot="❌";}
			
			$page_index.='<tr>
				<td style="text-align:center">'.$vornot.'</td>
				<td style="text-align:center">'.$mcp['id_user'].'</td>
				<td style="text-align:center"><a href="gestion/?act=99&f=1&id='.$mcp['id_user'].'">'.$mcp['mail'].'</a></td>
				<td style="text-align:center">'.$mcp['credit'].'</td>
				<td style="text-align:center"><a href="gestion/?act=99&f=1&id='.$mcp['id_user'].'">⚙️</a></td>
				<td style="text-align:center"><a href="gestion/?act=99&f=2&id='.$mcp['id_user'].'">🗑</a></td>
				
			</tr>';
			} 
		$page_index.='
		</tbody></table>
	</div>';
	
	echo $page_index;

}