<?php

require_once dirname(__FILE__).'/../config.php';
	try
	{
		$connexion = mysqli_connect($serveur, $nom_utilisateur, $pass, $base_de_donnee);
		mysqli_set_charset($connexion, "utf8");
		if (mysqli_connect_errno()) {
			exit("Failed to connect to MySQL");
		}
	}
	catch(Exception $e)
	{
       exit("Failed to connect to MySQL");
	}
	
define('TABLE_CAT',SQL_PREFIXE.'cat');
define('TABLE_PAGE',SQL_PREFIXE.'page');
define('TABLE_SECT',SQL_PREFIXE.'sect');
define('TABLE_SITE',SQL_PREFIXE.'site');
define('TABLE_USER',SQL_PREFIXE.'u');
define('TABLE_RELANCE',SQL_PREFIXE.'relance');

function rcp_site($id_site, $section, $order, $valide, $type, $limit){

	global $url_annuaire, $extension;
        
		$sect = "";
		$val = "";
		$site = array();
			
			if($valide == 1) // validé et en ligne
				{$val = " AND valide=1";}	
			elseif($valide == 2) // En attente de validation sans paiement nécessaire
				{$val = " AND valide=2";}
			elseif($valide == 3) // En attente de paiement	
				{$val = " AND valide=3";}
			elseif($valide == 4) // Potentiellement Payé
				{$val = " AND valide=4";}
			
			if($type == 1)
				{$type = " AND type=1";}		
			elseif($type == 2) 
				{$type = " AND type=2";}
			
			if (!empty($section))
				{$sect =  " AND sect=$section";}
			
			$id_site = intval($id_site);			
			if ($id_site>0)
				{$id =  "WHERE id_site=$id_site";}
			else
				{$id = "WHERE id_site!=0";}
			
			
		global $connexion;
		$req = mysqli_query($connexion,"SELECT id_site, sect, type, titre, url, ancre, url_retour, url_rss, description, mail_auteur, valide, compteur, note, DATE_FORMAT(dat, '%a, %d %b %Y %T') AS f_date, DATE_FORMAT(date2validation, '%a, %d %b %Y %T') AS v_date FROM ".TABLE_SITE." $id $val $type $sect $order $limit");
		
       	$res = mysqli_num_rows($req);
	
		if($res) {
		
			while($data = mysqli_fetch_assoc($req)){
			
					//$data['id_site']
					
					/* Données sur la section et la catégorie */
					$sect = rcp_sect($data['sect'], '', '');
					$se = $sect[0];
					$cat = rcp_cat($se['id_cat'], "");
					$c = $cat[0];
					
					$data['info']['section']['titre'] = $se['titre'];
					$data['info']['section']['permanlink'] = $url_annuaire.clean($c['titre']).'/2.'.$data['sect'].'-'.clean($se['titre']).$extension;
					$data['info']['cat']['titre'] = $c['titre'];
					$data['info']['cat']['permanlink'] = $url_annuaire.'3.'.$c['id_cat'].'-'.clean($c['titre']).$extension;
					
					$data['info']['permanlink'] = $url_annuaire.clean($c['titre']).'/'.clean($se['titre']).'/1.'.$data['id_site'].'-'.clean($data['titre']).$extension;
					$data['info']['amp'] = $url_annuaire.clean($c['titre']).'/'.clean($se['titre']).'/1.'.$data['id_site'].'-'.clean($data['titre']).'-amp'.$extension;
			
					$site[] = $data;
					
					//echo '<pre>';
					//var_dump($site); exit;
					
			}
	 
			return $site; 
		
		}
	
}

function rcp_sect($id_sect, $id_cat, $order){

	global $url_annuaire, $extension;

		$id = "";
		$cat= "";
		$sect = array();
		
			if (!empty($id_cat)) 
				$cat =  " AND id_cat=$id_cat";
			
			$id_sect = intval($id_sect);
			
			if ($id_sect>0)
				$id =  "WHERE id_sect=$id_sect";
			else
				$id = 'WHERE id_sect!=0';
		
		global $connexion;
		$req = mysqli_query($connexion,"SELECT id_sect, titre, id_cat, description, compteur FROM ".TABLE_SECT." $id $cat $order");
		
		$res = mysqli_num_rows($req);
	
		if($res) {
		
			while($data = mysqli_fetch_assoc($req)){
			
					$cat = rcp_cat($data['id_cat'], "");
					$c = $cat[0];
					
					$data['info']['cat']['titre'] = $c['titre'];
					$data['info']['cat']['permanlink'] = $url_annuaire.'3.'.$c['id_cat'].'-'.clean($c['titre']).$extension;
					
					$data['info']['permanlink'] = $url_annuaire.clean($c['titre']).'/2.'.$data['id_sect'].'-'.clean($data['titre']).$extension;
					$data['info']['amp'] = $url_annuaire.clean($c['titre']).'/2.'.$data['id_sect'].'-'.clean($data['titre']).'-amp'.$extension;
			
					$sect[] = $data;
					
			}
	 
			return $sect; 
		
		}
	


}

function rcp_cat($id_cat, $order){
	
	global $url_annuaire, $extension, $connexion;

		$id = "";
		$cat = array();
		
			$id_cat = intval($id_cat);
		
			if ($id_cat>0)
				$id =  "WHERE id_cat=$id_cat";
				
			else
				$id = 'WHERE id_cat!=0';
			
		$req = mysqli_query($connexion, "SELECT * FROM ".TABLE_CAT." $id $order");
		
		/* Vérification de la connexion */
		
		$res = mysqli_num_rows($req);
	
		if($res) {
		
			while($data = mysqli_fetch_assoc($req)) {
				$data['info']['permanlink'] = $url_annuaire.'3.'.$data['id_cat'].'-'.clean($data['titre']).$extension;
				$data['info']['amp'] = $url_annuaire.'3.'.$data['id_cat'].'-'.clean($data['titre']).'-amp'.$extension;
				$cat[] = $data;
			}
	 
			return $cat;
			
		}
	

}

function rcp_pro_credit($id_pro){

		global $connexion;
		$req = mysqli_query($connexion, "SELECT * FROM ".TABLE_USER." WHERE id_user=$id_pro");
		$res = mysqli_num_rows($req);
		if($res) {
		
			while($data = mysqli_fetch_assoc($req)) {
				$coins = $data['credit'];
			}
	 
			return $coins;
			
		}

}

function rcp_pro_websites($email, $order=NULL){

	global $url_annuaire, $extension, $connexion;
	
	if ($order == "url") {$order = "ORDER by url ASC";} 
	elseif ($order == "date") {$order = "ORDER by date2validation ASC";}
	else {$order = "ORDER by titre ASC ";}
        
	$req = mysqli_query($connexion,"SELECT * FROM ".TABLE_SITE." WHERE mail_auteur='$email' AND valide=1 $order"); 
		
    $res = mysqli_num_rows($req);
	
		if($res) {
		
			while($data = mysqli_fetch_assoc($req)){
			
					//$data['id_site']
					
					/* Données sur la section et la catégorie */
					$sect = rcp_sect($data['sect'], '', '');
					$se = $sect[0];
					$cat = rcp_cat($se['id_cat'], "");
					$c = $cat[0];
					
					$data['info']['section']['titre'] = $se['titre'];
					$data['info']['section']['permanlink'] = $url_annuaire.clean($c['titre']).'/2.'.$data['sect'].'-'.clean($se['titre']).$extension;
					$data['info']['cat']['titre'] = $c['titre'];
					$data['info']['cat']['permanlink'] = $url_annuaire.'3.'.$c['id_cat'].'-'.clean($c['titre']).$extension;
					
					$data['info']['permanlink'] = $url_annuaire.clean($c['titre']).'/'.clean($se['titre']).'/1.'.$data['id_site'].'-'.clean($data['titre']).$extension;
					$data['info']['quidam'] = $data['note'];
					$data['info']['url'] = $data['url'];
					$site[] = $data;
					
					//echo '<pre>';
					//var_dump($site); exit;
					
			}
	 
			return $site; 
		
		}
	
}

function rcp_page($id_page, $order){
	global $url_annuaire, $extension;

		$page = array();
		
			$id_page = intval($id_page);
		
			if ($id_page>0)
				$id =  "WHERE id_page=$id_page";
				
			else
				$id = 'WHERE id_page!=0';
		
		global $connexion;
		$req = mysqli_query($connexion, "SELECT id_page, titre, contenu, compteur FROM ".TABLE_PAGE." $id $order");
		
		$res = mysqli_num_rows($req);
	
		if($res) {
		
			while($data = mysqli_fetch_assoc($req)) {
				$data['info']['permanlink'] = $url_annuaire.'4.'.$data['id_page'].'-'.clean($data['titre']).$extension;
				$data['info']['amp'] = $url_annuaire.'4.'.$data['id_page'].'-'.clean($data['titre']).'-amp'.$extension;
				$page[] = $data;
			}
			return $page;
		}
	
}


function update_c ($chiffre, $id, $compteur) {
	global $connexion;
      $compteur_a_jour = $compteur + 1;
      
    switch ($chiffre) {
		case 1:
		$sql = "UPDATE ".TABLE_SITE." SET  compteur='".$compteur_a_jour."' WHERE id_site = '".(int)$id."' LIMIT 1";
		break;
      
		case 2:
		$sql = "UPDATE ".TABLE_SECT." SET compteur='".$compteur_a_jour."' WHERE id_sect = '".(int)$id."' LIMIT 1";
		break;
      
		case 3:
		$sql = "UPDATE ".TABLE_CAT." SET compteur='".$compteur_a_jour."' WHERE id_cat = '".(int)$id."' LIMIT 1";
		break;
		  
		case 4:
		$sql = "UPDATE ".TABLE_PAGE." SET compteur='".$compteur_a_jour."' WHERE id_page = '".(int)$id."' LIMIT 1";
		break;
	}      
      
      if (isset($sql)) {
      $res = mysqli_query($connexion,$sql);
        if ($res) return true;
        else return false;
      }
      else return false;
}

function count_page ($id_sect) {

	global $connexion;
	$req = mysqli_query($connexion,"SELECT COUNT(id_site) FROM ".TABLE_SITE." WHERE valide=1 AND sect=$id_sect");
	
	$data = mysqli_fetch_array($req);
	
	if ($data)
	
	return $data[0];

}

function stat_site ($valide, $type) {
	// valide = 1 les sites validé, $type = 1 les sites ayant une fiche
	global $connexion;
	$type1 = "";
	if(isset($type) && $type != '') {
		$type1 = 'AND type = '.$type;
	}
	if(intval($valide)!=1) {
		$req = mysqli_query($connexion, "SELECT COUNT(id_site) FROM " . TABLE_SITE . " WHERE valide IN (2,3,4) $type1");
	} else {
		$req = mysqli_query($connexion,"SELECT COUNT(id_site) FROM ".TABLE_SITE." WHERE valide=1 $type1");	
	}
	$data = mysqli_fetch_array($req);
	if ($data)
	return $data[0];

}

function stat_section ($cat) {
	global $connexion;
	if(intval($cat)!=0) {
		$req = mysqli_query($connexion,"SELECT COUNT(id_sect) FROM ".TABLE_SECT." WHERE id_cat = $cat");
	} else {
		$req = mysqli_query($connexion,"SELECT COUNT(id_sect) FROM ".TABLE_SECT."");
	}
	if($req) {
	$data = mysqli_fetch_array($req);
		if ($data) return $data[0];
   }
}

function stat_categorie () {
	global $connexion;
	$req = mysqli_query($connexion,"SELECT COUNT(id_cat) FROM ".TABLE_CAT."");   
	if($req)  {
		$data = mysqli_fetch_array($req);
		if ($data) return $data[0];
	}

}

function valid_donnees($donnees){
        $donnees = trim($donnees);
        $donnees = stripslashes($donnees);
        $donnees = htmlspecialchars($donnees);
		$donnees = strip_tags($donnees);
		$donnees = str_replace('"', '', $donnees);
		$donnees = str_replace("'","&#039;",$donnees);
		$donnees = str_replace(">","&gt;",$donnees);
		$donnees = str_replace("<","&lt;",$donnees);
		$injections = array('/(\n+)/i',
                '/(\r+)/i',
                '/(\t+)/i',
                '/(%0A+)/i',
                '/(%0D+)/i',
                '/(%08+)/i',
                '/(%09+)/i'
                );
        $donnees = preg_replace($injections,'',$donnees);
        return $donnees;
}

function valid_donnees_light($donnees){ 
		$donnees = trim($donnees);
		$donnees = stripslashes($donnees);
        $donnees = htmlspecialchars($donnees);
		$donnees = str_replace('"', '&quot;', $donnees);
		$donnees = str_replace("'","&#039;",$donnees);
		$donnees = str_ireplace("h1","h2",$donnees);
		$donnees = str_ireplace("src","",$donnees);
		$donnees = str_ireplace("style=","",$donnees);
		$donnees = str_ireplace("class=","",$donnees);
		$donnees = str_ireplace("id=","",$donnees);
		$donnees = str_ireplace("action=","",$donnees);
		$donnees = str_ireplace("get=","",$donnees);
		$donnees = str_ireplace("onclick","",$donnees);
		$donnees = str_ireplace("onload","",$donnees);
		$donnees = str_ireplace("for=","",$donnees);
		$donnees = str_ireplace("script","scrpt",$donnees);
		$donnees = str_ireplace("<form","form",$donnees);
		$donnees = str_ireplace("href","",$donnees);
		$donnees = str_ireplace(".js","",$donnees);
		$donnees = str_ireplace("button","",$donnees);
		$donnees = str_ireplace("input","",$donnees);
		$donnees = str_ireplace("<style","style",$donnees);
		$donnees = str_ireplace("<link","link",$donnees);
		$injections = array('/(\n+)/i',
                '/(\r+)/i',
                '/(\t+)/i',
                '/(%0A+)/i',
                '/(%0D+)/i',
                '/(%08+)/i',
                '/(%09+)/i'
                );
        $donnees = preg_replace($injections,'',$donnees);
        return $donnees;
}

function scooter_form_malicious_scan($input) {
	$malicious = false;
	$suspects = array( "\r", "\n", "mime-version", "content-type", "bcc:", "cc:", "to:", "<", ">", "&lt;", "&rt;", "a href", "/a", "/URL", "URL=", "<h1>" );
	foreach ( $suspects as $suspect ) {
		if ( strpos(strtolower($input), strtolower($suspect) ) !== false ) {
			$malicious = true;
			break;
		}
	}
	return $malicious;
}

function scooter_form_malicious_scan_light($input) {
	$malicious = false;
	$suspects = array( "\r", "\n", "mime-version", "content-type", "bcc:", "cc:", "to:", "a href", "/a", "/URL", "URL=" );
	foreach ( $suspects as $suspect ) {
		if ( strpos(strtolower($input), strtolower($suspect) ) !== false ) {
			$malicious = true;
			break;
		}
	}
	return $malicious;
}

function pop2modo() {
	
	global $titre_annuaire, $url_annuaire, $mail;
	$lagestion = $url_annuaire."gestion";
	
	$destinataire = $mail;
	$sujet = "🔥 1 nouveau site vient d'être proposé sur $titre_annuaire";
	$headers = 'Mime-Version: 1.0'."\r\n";
	$headers .= 'Content-type: text/html; charset=utf-8'."\r\n";
	$headers .= "From: $titre_annuaire <$mail>"."\r\n";
	$lagestion = $url_annuaire."gestion";
	
	$message = "<h1>Nouvelle soumission sur $titre_annuaire </h1><p>Pour administrer votre annuaire :</p><ol><li>Rendez-vous sur cette url ➡️<a href='$lagestion'>$lagestion</a></li><li>Validez ou refusez le site</li><li>Videz  (ou pas) le cache !</li></ol><p>Bonne journée !</p>";
	
	
	mail($destinataire, $sujet, $message, $headers);
	
}

function pop2modo_pro($id_du_dernier_enregistrement, $titrekrang) {
	
	global $titre_annuaire, $url_annuaire, $mail, $extension;
		
	$destinataire = $mail;
	$headers = 'Mime-Version: 1.0'."\r\n";
	$headers .= 'Content-type: text/html; charset=utf-8'."\r\n";
	$headers .= "From: $titre_annuaire <$mail>"."\r\n";


	$elpermalinkos = $url_annuaire.'1.'.$id_du_dernier_enregistrement.'-'.clean($titrekrang).$extension;
	$elpermalinkos = "<strong>[ <a href='$elpermalinkos'>Lien vers la fiche site</a> ]</strong>";

	$sujet = "🔥 1 nouveau site d'un partenaire PREMIUM vient d'être validé sur $titre_annuaire";
	$message = "<h1>Nouvelle validation d'un Site PRO sur $titre_annuaire </h1><p>👀 Et si vous jetiez un oeil pour vérifier si tout est conforme ?!</p><p>$elpermalinkos</p><p>Bonne journée !</p>";

	
	mail($destinataire, $sujet, $message, $headers);
	
}

function pop2modo_pro_update($id_du_site, $titrekrang) {
	
	global $titre_annuaire, $url_annuaire, $mail, $extension;
		
	$destinataire = $mail;
	$headers = 'Mime-Version: 1.0'."\r\n";
	$headers .= 'Content-type: text/html; charset=utf-8'."\r\n";
	$headers .= "From: $titre_annuaire <$mail>"."\r\n";


	$elpermalinkos = $url_annuaire.'1.'.$id_du_site.'-'.clean($titrekrang).$extension;
	$elpermalinkos = "<strong>[ <a href='$elpermalinkos'>Lien vers la fiche site</a> ]</strong>";

	$sujet = "🔥 [MODIFICATION] d'un site PRO sur $titre_annuaire";
	$message = "<h1>1 partenaire PREMIUM vient de modifier son site sur $titre_annuaire</h1><p>👀 Et si vous jetiez un oeil pour vérifier si tout est conforme ?!</p><p>$elpermalinkos</p><p>Bonne journée !</p>";

	
	mail($destinataire, $sujet, $message, $headers);
	
}

function self_upgrade4pro ($id_pro, $mail_pro, $id_site) {
	
		global $connexion, $titre_annuaire, $url_annuaire, $mail;
		
		if ((!isset($id_pro)) OR (!isset($mail_pro)) OR (!isset($id_site))) {exit('<p>Oups ca va coincer !</p>');} // Si il manque qqch on coupe
		
		// Reste-t-il des crédits au pro ?
		$coins = rcp_pro_credit($id_pro); 
		
		// On récupère les données du site
		$bb = rcp_site(intval($id_site), '', '', '', '', ''); 
			
		// Si toutes les éventualités suivantes ne se sont pas produites => on tente l'upgrade || Sinon on renvoie une erreur.
		if ($coins <= 0) {$qqchcloche = "Vous n'avez plus suffisamment de crédits pour effectuer cette opération. Vous devez recharger des crédits pour pouvoir booster ce site.";}
		elseif ($bb[0]["mail_auteur"] != $mail_pro) {$qqchcloche = "Vous n'avez pas la possibilité de modifier ce site. Merci de <a href='contact.html'>contacter un administrateur</a> pour plus d'informations.";}
		elseif ($bb[0]["note"] >= 70) {$qqchcloche = "Ce site a déjà été boosté. Que voulez-vous de plus ?!";}
		elseif ($bb[0]["valide"] == 2) {$qqchcloche = "Ce site n'a pas encore été validé. Patience !";}
		else {$qqchcloche = "";} // Non tout va bien, on peut lancer la machine !
		
		if ($qqchcloche == "")
		{
						
			$sql = "
			UPDATE ".TABLE_USER." SET credit = credit - 1 WHERE id_user = '$id_pro';
			UPDATE ".TABLE_SITE." SET note = 70 WHERE id_site = '$id_site';
			";

			if(mysqli_multi_query($connexion, $sql)){		
					
				$destinataire = $mail_pro;
				$sujet = "🚀 Votre site ID # $id_site vient d'être boosté avec succès !";
				$headers = 'Mime-Version: 1.0'."\r\n";
				$headers .= 'Content-type: text/html; charset=utf-8'."\r\n";
				$headers .= "From: $titre_annuaire <$mail>"."\r\n";
				$message = "<h1>🚀 Bonne nouvelle 🚀</h1><p>🚀 Votre site ID # $id_site vient d'être boosté avec succès sur $titre_annuaire.<br />🚀 Celui-ci apparaitra désormais dans les toutes premières positions de la section dans laquelle il a été répertorié et bénéficiera d'un lien supplémentaire directement depuis cette section !</p>";
				
				mail($destinataire, $sujet, $message, $headers);
				
				$cadonnekoi = "<h1>🚀 Décollage réussi Capitaine Haddock</h1><p>🚀 Votre site ID # $id_site vient d'être boosté avec succès sur $titre_annuaire. Celui-ci apparaitra désormais dans les toutes premières positions de la section dans laquelle il a été répertorié et bénéficiera d'un lien supplémentaire directement depuis cette section !</p><p><a href='javascript:history.go(-1)'>🔙 Retour</a>";
				vider_cache('cache');
				
			}
			
			else {
				
				//echo mysqli_error($connexion);
				$cadonnekoi = $sql;
			}
			
		} else {
			
			$cadonnekoi = "<h1>Erreur</h1><p>$qqchcloche</p>";
			
		}
		
		return $cadonnekoi;
		
}

function self_updatewebsite() {
	global $connexion;
	global $_POST;
	global $nb_cara_description;
	
	$id = valid_donnees($_POST["id"]);
	$titre = valid_donnees($_POST["titre"]);
	$url = valid_donnees($_POST["url"]);
	$ancre = valid_donnees($_POST["ancre"]);
	$url_retour = valid_donnees($_POST["url_retour"]);
	$url_rss = valid_donnees($_POST["url_rss"]); 
	$sect = valid_donnees($_POST["sect"]);
	$mail_auteur = valid_donnees($_POST["mail_auteur"]);
	$description = valid_donnees_light($_POST["description"]);
	
	$mail_session = $_SESSION['mail_user'];
		
	// Construction des messages de retour
	$msg_erreur = "<p>Grosse erreur, un ou plusieurs des champs ne sont pas bon :</p><ul>";
	$msg_ok = "<p class='align_center'>🔥 ROYAL : Votre site a bien été modifié ‍🔥</p>";
	$message_retour = "<p class='recommencer'>J'ai bien lu les consignes ci-dessus et je corrige mes erreurs : <a href='javascript:history.go(-1)'>Retourner sur le formulaire</a></p>";
	$message = $msg_erreur;
	
	
	if (empty($id))
		{$message .= '<li style="color:red;">Heu ? On parle de quel site au juste là ?!</li>';$e=1;}
	
	$bb = rcp_site(intval($id), '', '', '', '', ''); 
		
	if (empty($bb))
		{$message .= '<li style="color:red;">Heu ? On parle de quel site au juste là ?!</li>';$e=1;}
	
	if ($bb[0]["mail_auteur"] != $mail_session) 
		{$message .= '<li style="color:red;">Impossible de modifier ce site. Merci de <a href=\'contact.html\'>prendre contact avec un administrateur</a> pour plus d\'informations.</li>';$e=1;}
	
	if ($bb[0]["valide"] == 2) 
		{$message .= '<li style="color:red;">Ce site n\'a pas encore été validé. Patience !</li>';$e=1;}

	if (empty($titre))
		{$message .= '<li style="color:red;">Veuillez donner un titre à votre site</li>';$e=1;}
		
	if (empty($description))
		{$message .= '<li style="color:red;">Veuillez saisir une description</li>';$e=1;}

	if (strlen($description)<$nb_cara_description)
		{$message .= '<li style="color:red;">Votre description est trop courte.</li>';$e=1;}
		
	if (strlen($description)>50000)
		{$message .= '<li style="color:red;">Votre description est trop longue</li>';$e=1;} 
		
	if (scooter_form_malicious_scan_light($description) == true){$message .= '<li style="color:red;">Votre description contient des caractères interdits</li>';$e=1;} 		

	if (empty($url))
		{$message .= '<li style="color:red;">L\'url de votre site est incorrecte</li>';$e=1;}
		
	if (!empty($url_rss)) {
		if ($url_rss == $url)
		{$message .= '<li style="color:red;">L\'url de votre flux RSS est incorrecte</li>';$e=1;} 
	}
		
	if (empty($sect))
		{$message .= '<li style="color:red;">Veuillez choisir une catégorie.</li>';$e=1;}	
	
	if ($e>0) {
		$message .= "</ul>";
	}
	
	if (strlen($message) > strlen($msg_erreur)) {
		
		echo $message;
		echo $message_retour;
		
	} else {
	
			$sql = "UPDATE ".TABLE_SITE." SET titre='".$titre."',  url='".$url."', ancre='".$ancre."', url_rss='".$url_rss."', sect='".$sect."', description='".$description."' WHERE id_site = '".$id."'";
			
			$res = mysqli_query($connexion, $sql);
		
		if ($res) {
			
		pop2modo_pro_update ($id, $titre);
		return $msg_ok;
		vider_cache('cache');
	
		} else {
			//echo mysqli_error($connexion);
			echo"<p>OUCHHH 😭 Il y a eu un petit problème lors de l'enregistrement MYSQL</p>";
		}
	
	}

}

function mail_site_en_attente ($mail_auteur, $url, $id_du_dernier_enregistrement = FALSE) {

	global $titre_annuaire, $url_annuaire, $mail, $paypal_id, $mode_vacances;
	
	$destinataire = $mail_auteur;
	$sujet = "💾 Enregistrement de votre site sur $titre_annuaire";
	
	$message = file_get_contents((dirname(__FILE__).'/../config/mail_en_attente.php'));
	$message = str_replace('%url_annuaire%', $url_annuaire, $message);
	$message = str_replace('%titre_annuaire%', stripslashes($titre_annuaire), $message);
	$message = str_replace('%titre_site%', $url, $message);
	
	if ((!empty($paypal_id)) AND ($mode_vacances == FALSE) AND (isset($id_du_dernier_enregistrement))) {
		$elpermalinkos = $url_annuaire.'accelerer.html?id='. $id_du_dernier_enregistrement. '';
		$elpermalinkos = "<h3>🚀 Demandez la validation en EXPRESS !</h3><p>Accélérez la validation de votre site dès maintenant ! <a href='$elpermalinkos'>Cliquez ici pour en savoir plus</a>";
		$message = str_replace('%URL_FASTLANE%', $elpermalinkos, $message);
	} else {
		$message = str_replace('%URL_FASTLANE%', "", $message);
	}
	
	$headers = 'Mime-Version: 1.0'."\r\n";
	$headers .= 'Content-type: text/html; charset=utf-8'."\r\n";
	$headers .= "From: $titre_annuaire <$mail>"."\r\n";
	$headers .= "Reply-To: $titre_annuaire <$mail>"."\r\n";
	
	mail($destinataire, $sujet, $message, $headers);
}

function mail_site_en_attente_de_payement($mail_auteur, $url, $id_du_dernier_enregistrement = false){
	
    global $titre_annuaire, $url_annuaire, $mail, $paypal_id, $mode_vacances;

    $destinataire = $mail_auteur;
    $sujet = "💳 Paiement requis pour la validation de votre site sur $titre_annuaire";
    $message = file_get_contents(dirname(__FILE__) . '/../config/mail_en_attente_payement.php');


    $message = str_replace('%url_annuaire%', $url_annuaire, $message);
    $message = str_replace('%titre_annuaire%', stripslashes($titre_annuaire), $message);
    $message = str_replace('%titre_site%', $url, $message);

    if (!empty($paypal_id) && $mode_vacances === false && $id_du_dernier_enregistrement) {
        $permalink_fastlane  = $url_annuaire . 'finaliser.html?id=' . $id_du_dernier_enregistrement;
        $bloc_fastlane  = "<h3>🚀 Finalisez votre inscription !</h3>
                           <p>Pour finaliser votre inscription dès maintenant&nbsp;:
                           <a href='$permalink_fastlane'>cliquez ici pour accèder à la page de paiement</a>.</p>";
        $message = str_replace('%URL_FASTLANE%', $bloc_fastlane, $message);
    } else {
        $message = str_replace('%URL_FASTLANE%', '', $message);
    }

    $headers  = "Mime-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=utf-8\r\n";
    $headers .= "From: $titre_annuaire <$mail>\r\n";
    $headers .= "Reply-To: $titre_annuaire <$mail>\r\n";

    mail($destinataire, $sujet, $message, $headers);
}

function mail_site_validation_express ($mail_auteur, $url, $id_du_dernier_enregistrement, $titrekrang) {

	global $titre_annuaire, $url_annuaire, $mail, $extension;
	
	$destinataire = $mail_auteur;
	$sujet = "👌 Validation Express de votre site # $id_du_dernier_enregistrement sur $titre_annuaire";
	
	$message = file_get_contents((dirname(__FILE__).'/../config/mail_validation_express.php'));
	$message = str_replace('%url_annuaire%', $url_annuaire, $message);
	$message = str_replace('%titre_annuaire%', stripslashes($titre_annuaire), $message);
	$message = str_replace('%titre_site%', $url, $message);

	$elpermalinkos = $url_annuaire.'1.'.$id_du_dernier_enregistrement.'-'.clean($titrekrang).$extension;
	$elpermalinkos = "📌 Voici votre <a href='$elpermalinkos'>fiche site</a>";
	$message = str_replace('%urlfichesite%', $elpermalinkos, $message);

	$headers = 'Mime-Version: 1.0'."\r\n";
	$headers .= 'Content-type: text/html; charset=utf-8'."\r\n";
	$headers .= "From: $titre_annuaire <$mail>"."\r\n";
	$headers .= "Reply-To: $titre_annuaire <$mail>"."\r\n";
	
	mail($destinataire, $sujet, $message, $headers);
}


function site_register($description_c, $fastpass, $coins) {
	
	global $connexion;
	global $_POST;
	global $nb_cara_description;
	global $msg2modo;
	global $forcebacklink;
	global $payant_only_end_of_tunel;
	
	$titre = valid_donnees($_POST["titre"]);
	$url = valid_donnees($_POST["url"]);
	$ancre = valid_donnees($_POST["ancre"]);
	$url_retour = valid_donnees($_POST["url_retour"]);
	$url_rss = valid_donnees($_POST["url_rss"]); 
	$sect = valid_donnees($_POST["sect"]);
	$mail_auteur = valid_donnees($_POST["mail_auteur"]);
	
	if ($fastpass == "yes") {
		
		$description = valid_donnees_light($_POST["description"]);
		
	} else {
		
		$description = valid_donnees($_POST["description"]);
		
	}
	
	$dat = date("Y-m-d H:i:s");
	
	// Construction des messages de retour
	$msg_erreur = "<p>Grosse erreur, un ou plusieurs des champs ne sont pas bon :</p><ul>";
	
	if ($fastpass == "yes")	{
		
		$msg_ok = "<p class='align_center'>🔥🔥🔥 LIKE A BO$$ 🔥🔥🔥 : Site validé automatiquement‍</p><p class='align_center'>Allez, on enchaine ?!</p><p class='align_center'><a href='ajouter.html'>👑 Ajouter un autre site</a></p>";
		
	} else {
		
		if ($payant_only_end_of_tunel == true) {
			
			$msg_ok = "<p>✅ Etape 1/2 terminée. Nous avons bien reçu votre soumission. Pour valider votre inscription, merci de procéder au paiement via le formulaire Paypal ci-dessous.<br/>💡 Si vous avez plusieurs sites à nous proposer, <a href='/contact.html'>contactez-nous</a> pour ouvrir un compte pro et profiter de tarifs plus avantageux.</p>";
			
		} else {
			
			$msg_ok = "<p>✅ Site proposé avec succès ! Un modérateur le validera le plus vite possible. Nous vous remercions d'avoir choisi notre annuaire et vous souhaitons un agréable surf 🏄‍</p>";
			
			if ($forcebacklink != "oui") {
				
				$msg_ok .= "<p><span style='color:red;font-weight:bold;background:yellow;'>💡 Augmentez vos chances de voir votre site validé sur notre annuaire en insérant un lien retour sur votre site !</span></p>";
				
			}
			
		}
		
	}
	
	$message_retour = "<p class='recommencer'>J'ai bien lu les consignes ci-dessus et je corrige mes erreurs : <a href='javascript:history.go(-1)'>Retourner sur le formulaire</a></p>";
	
	$message = $msg_erreur;
	
	if ($_SESSION['statut'] == "kaioh")	{  // Si on est admin, credit illimité !
		
		$coins = 1;
		
	}
	
	if ($coins < 1){
		$message .= '<li style="color:red;">Vous n\'avez plus de crédits pro. Veuillez <a href="contact.html">nous contacter</a> pour recharger des crédits et conserver vos avantages partenaires, ou vous <a href="gestion/logout.php">déconnecter</a> pour proposer votre site en tant qu\'invité sans tous les avantages associés au compte pro.</li>';
		$e=1;
	} 
	
	if (empty($titre)) {
		
		$message .= '<li style="color:red;">Veuillez donner un titre à votre site</li>';
		$e=1;
		
	} else {
		
		$_SESSION['pro_titre'] = $titre;
		
	}
	
	if (empty($description)){
		
		$message .= '<li style="color:red;">Veuillez saisir une description</li>';
		$e=1;
		
	} else {
		
		$_SESSION['pro_description'] = $description;
		
	}
	
	if ($_SESSION['statut'] != "kaioh")	{

		// Si on est admin, pas de restrictions sur les descriptions !
		
		if (strlen($description)<$nb_cara_description){
			
			$message .= '<li style="color:red;">Votre description est trop courte.</li>';
			$e=1;
			
		} else {
			
			$_SESSION['pro_description'] = $description;
			
		}
		
		if (strlen($description)>50000)	{
			
			$message .= '<li style="color:red;">Votre description est trop longue</li>';
			$e=1;
			
		} else {
			
			$_SESSION['pro_description'] = $description;
			
		}
		
		if ($_SESSION['statut'] == "pr0") {
			
			if (scooter_form_malicious_scan_light($description) == true){
				
					$message .= '<li style="color:red;">Votre description contient des caractères interdits</li>';
					$e=1;
					
			} else {
					
					$_SESSION['pro_description'] = $description;
					
			}
				
		} else {
			
			if (scooter_form_malicious_scan($description) == true){
				
				$message .= '<li style="color:red;">Votre description contient des caractères interdits</li>';
				$e=1;
				session_destroy ();
				
			} else {
					
					$_SESSION['pro_description'] = $description;
					
			}			
		}
	}
	
	if (empty($url)){
		
		$message .= '<li style="color:red;">L\'url de votre site est incorrecte</li>';
		$e=1;
		
	} else {
		
		$_SESSION['pro_url'] = $url;
		
	}
	
	if (!empty($url_rss)) {
		
		if ($url_rss == $url){
			
			$message .= '<li style="color:red;">L\'url de votre flux RSS est incorrecte</li>';
			$e=1;
			
		} else {
			
			$_SESSION['pro_url_rss'] = $url_rss;
			
		}
	}
	
	$nomdedomainedelannuaire = $_SERVER['SERVER_NAME'];
	
	if (strpos($url_retour, $nomdedomainedelannuaire) !== false) {
		
		$message .= '<li style="color:red;">Merci de mettre un lien retour valide</li>';
		$e=1;
		
	} else {
		
		$_SESSION['pro_url_retour'] = $url_retour;
		
	}
	
	if ($fastpass != "yes") { // Si pas de FastPass, activation eventuelle du lien retour obligatoire
		
		if ($forcebacklink == "oui") { // Si lien retour obligatoire
		
			if (empty($url_retour)){ // Si vide ==> Erreur
			
				$message .= '<li style="color:red;">Veuillez nous indiquer la page sur laquelle se trouve le lien retour.</li>';
				$e=1;
			
			}
			
		}
		
	}
	
	if (empty($mail_auteur)){
		
		$message .= '<li style="color:red;">Veuillez saisir votre adresse email</li>';
		$e=1;
		
	} else {
		
		$_SESSION['pro_mail_auteur'] = $mail_auteur;
		
	}
	
	if (valid_mail($mail_auteur) == 1) {
		
		$message .= '<li style="color:red;">Votre mail n\'est pas correcte ou l\'hébergeur de votre boîte mail n\'est pas autorisé</li>';
		$e=1;
		
	} else {
		
		$_SESSION['pro_mail_auteur'] = $mail_auteur;
		
	}
	
	if (empty($sect)){
		
		$message .= '<li style="color:red;">Veuillez choisir une catégorie.</li>';
		$e=1;
		
	}
	
	if (!empty($ancre)) {
		
		$_SESSION['ancre'] = $ancre;
		
	}

	$compare_url = url_www($url);
	
	$sql = mysqli_num_rows(mysqli_query($connexion,"SELECT * FROM ".TABLE_SITE." WHERE url LIKE '%$compare_url%' LIMIT 1"));
	
	if ($sql==1) {
		
		$message .= '<li style="color:red;">L\'url de votre site est déjà présente dans la base de donnée de l\'annuaire</li>';
		$e=1;
		
	}

	if ($fastpass != "yes") { // Si pas de FastPass, on check le CAPTCHA, sinon on fait confiance
		
		
		if( $_SESSION['kap_tcha_id'] == $_POST['security_code'] && !empty($_SESSION['kap_tcha_id'] ) ) {
			
		} else {
			
			$message .= '<li style="color:red;">Le code de sécurité est incorrect. Seriez vous un Bot ?</li>';
			$e=1;
		}
		
	}
	
	if ($e>0) {
		
		$message .= "</ul>";
		
	}
	
	if (strlen($message) > strlen($msg_erreur)) {
		
		$message .= $message_retour;
		
		echo $message;
		return "kwak";
		
	} else {
		
		
		
		if (strlen($description) > intval($description_c)) { //Choix si oui ou non le site aura une fiche en fonction du chiffre $description_c
			
			$type = 1;
			$description = str_replace(CHR(10), "<br />", $description); 
			
		} else { 
		
			$type = 2; 
			
		}
		
		if ($fastpass == "yes") { // Multiquery
		
			if ($_SESSION['statut'] == "kaioh")	{
				
				$juice=150;
				
			} else {
				
				$juice=70;
				
			}
			
			$newcreditcoins = $coins - 1;
			
			$sql = "INSERT INTO ".TABLE_SITE." (sect,type,titre,url,ancre,url_retour,url_rss,description,mail_auteur,valide,compteur,note,dat,date2validation) VALUES ('".intval($sect)."', '".$type."', '".$titre."',	'".$url."', '".$ancre."', '".$url_retour."', '".$url_rss."', '".$description."', '".$mail_auteur."', '1', '2', '".$juice."', '".$dat."', '".$dat."'); UPDATE ".TABLE_USER." SET credit='$newcreditcoins' WHERE mail = '$mail_auteur';";
			
			$res = mysqli_multi_query($connexion, $sql);
			
			vider_cache('cache');
		
		} else { // Query Simple
		
			if (!empty($url_retour)) {
				
				$juice=20;
				
			} else {
				
				$juice=10;
				
			}
			
			if ($payant_only_end_of_tunel) {
				$statut_before_payment = '3';
			} else {
				$statut_before_payment = '2';
			}
		
			$sql = "INSERT INTO ".TABLE_SITE." (sect,type,titre,url,ancre,url_retour,url_rss,description,mail_auteur,valide,compteur,note,dat,date2validation) VALUES ('".intval($sect)."', '".$type."', '".$titre."',	'".$url."', '".$ancre."', '".$url_retour."', '".$url_rss."', '".$description."', '".$mail_auteur."', '".$statut_before_payment."', '2', '".$juice."', '".$dat."','1000-01-01 00:00:00');";
			
			$res = mysqli_query($connexion,$sql);
		
		}
			  
		if ($res) {
			
			$id_du_dernier_enregistrement = mysqli_insert_id($connexion);
			
			if ($fastpass != "yes") { // Si webmaster lambda, on détruit toute la session après soumission
			
				if (!$payant_only_end_of_tunel) {
					mail_site_en_attente($mail_auteur, $url, $id_du_dernier_enregistrement);
					session_destroy ();
				} else {
					mail_site_en_attente_de_payement($mail_auteur, $url, $id_du_dernier_enregistrement);
					session_destroy ();
				}
			
			} else {
				// Si Fastpass on détruit que les champs du site pour ne pas déconnecter le PRO
				
				if ($_SESSION['statut'] != "kaioh") { // Si compte PRO, On prévient l'admin quand nouvelle soumission
					pop2modo_pro($id_du_dernier_enregistrement, $titre);
					mail_site_validation_express ($mail_auteur, $url, $id_du_dernier_enregistrement, $titre);
				} 
				
				unset($_SESSION['pro_titre']);
				unset($_SESSION['pro_description']);
				unset($_SESSION['pro_url']);
				unset($_SESSION['pro_url_rss']);
				unset($_SESSION['ancre']);
			}
			
			if ($_SESSION['statut'] != "kaioh") { // Si on est pas admin
			
				// Module d'envoi du message a l'admin
				if ($msg2modo == 'all') {// Systematique
					
					pop2modo();
					
				} elseif ($msg2modo == 'spoted') { // Avec lien retour
					
					if ($url_retour != '') {
						
						pop2modo();
						
					}
					
				}
				
			}
			
			echo $msg_ok;
			return "ok";
	
		} else {
			
			//echo mysqli_error($connexion);
			
			echo "<p>OUCHHH 😭 Il y a eu un petit problème lors de l'enregistrement MYSQL</p>";
			return "kwak";
			
		}
	
	}

}