<?php
require_once dirname(__FILE__).'/upgrade.php';

function cut_str($str, $length){
$str = strip_tags($str);
if (strlen($str) < $length) return $str;
$s = substr($str, 0, $length+1);
while ($s[strlen($s)-1] != " ")
$s = substr($s, 0, strlen($s)-1);
 return substr($s, 0, strlen($s)-1)."[...]";
 }
 
function broken_html ($texte) {
	$texte = strip_tags($texte);
	$texte = str_replace('"', '', $texte);
	$texte = stripslashes($texte);
	$texte = str_replace("'","&#039;",$texte);
	$texte = str_replace(">","&gt;",$texte);
	$texte = str_replace("<","&lt;",$texte);
	return $texte;
}

function clean($str){
	$str = str_replace('&','et',$str);
	
	if($str !== mb_convert_encoding(mb_convert_encoding($str,'UTF-32','UTF-8'),'UTF-8','UTF-32'))
		
		$str = mb_convert_encoding($str,'UTF-8');
		
	$str = htmlentities($str,ENT_NOQUOTES,'UTF-8');
	
	$str = preg_replace('`&([a-z]{1,2})(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig);`i','$1',$str);
	
	$str = preg_replace(array('`[^a-z0-9]`i','`[-]+`'),'-',$str);
	
	$str = strtolower(trim($str,'-'));
	
	return $str;
}

function head() {

	include dirname(__FILE__).'/../config.php';
	
	$generator = '<meta name="generator" content="Janembart X Bartemis" />'."\n";
	
	$head = "$generator";
	
if (isset($_GET['act'])){
	
	// Fiche site
	if($_GET['act']=='1'){
	
		$id_site = intval($_GET['id']);
		
		$site = rcp_site($id_site, '', '', '', '', '');
		
		$s = $site[0];
		
		update_c (1, $id_site, $s['compteur']);
					
		if ($s['type'] == 1 AND $s['valide'] == 1) {
							
			$head.= '<title>'.broken_html($s['titre']).' - '.url_www ($s['url']).' - '.$titre_annuaire.'</title>'."\n";
			$head.= '<meta name="description" content="'.broken_html ($s['titre']).' : '.broken_html(cut_str ($s['description'],125)).'" />'."\n";
			$head.= '<link rel="canonical" href="'.$s['info']['permanlink'].'" />'."\n";
			$head.= '<link rel="amphtml" href="'.$s['info']['permanlink'].'?amp=1" />'."\n";
			$head.= '<meta property="og:title" content="Découvrez '.broken_html($s['titre']).' &#x266e; répertorié sur '.$titre_annuaire.'" />'."\n";
			
		}
		
		else {
		
			$head = '<meta http-equiv="Refresh" content="0;URL='.$url_annuaire.'" />'."\n";
			$head.= '<meta name="robots" content="noindex">'."\n";
							
		}
			
	}
	
	//Section
	elseif($_GET['act']=='2'){
		
		$id_sect = intval($_GET['id']);
		
		$sect = rcp_sect($id_sect, "", "");
		
		$se = $sect[0];
			
		update_c (2, $id_sect, $se['compteur']);
						
		$pt = intval($_GET['p']);
						
		if (!empty($pt))
			$page = ' '.$pt; 
						
		$head.= '<title>Section '.broken_html ($se['titre']).$page.' ( '.broken_html ($se['info']['cat']['titre']).' ) - '.$titre_annuaire.'</title>'."\n";
		$head.= '<meta name="description" content="Liste de sites de qualité parlant de '.broken_html ($se['titre']).' qui fait partie de la catégorie '.broken_html ($se['info']['cat']['titre']).' du site '.$titre_annuaire.'" />'."\n";
						
		if (intval($_GET['p']) == '0')
		{
			$head.= '<link rel="canonical" href="'.$se['info']['permanlink'].'" />'."\n";
			$head.= '<link rel="amphtml" href="'.$se['info']['permanlink'].'?amp=1" />'."\n";
		}
                     
		elseif (isset($_GET['p']))
		{
			$head.= '<link rel="canonical" href="'.$se['info']['permanlink'].'?p='.intval($_GET['p']).'" />'."\n";
			$head.= '<link rel="amphtml" href="'.$se['info']['permanlink'].'?p='.intval($_GET['p']).'&amp=1" />'."\n";
		}
			
		else 
		{
			$head.= '<link rel="canonical" href="'.$se['info']['permanlink'].'" />'."\n";
			$head.= '<link rel="amphtml" href="'.$se['info']['permanlink'].'?amp=1" />'."\n";
		}
						
		
	}
	
	//Categorie
	elseif($_GET['act']=='3'){
		
		$id_cat = intval($_GET['id']);
		
		$cat = rcp_cat($id_cat, '');
		
		$c = $cat[0];
			
		update_c (3, $id_cat, $c['compteur']);
		
		$head.= '<title>Catégorie '.broken_html ($c['titre']).' sur '.$titre_annuaire.'</title>'."\n";
		$head.= '<meta name="description" content="Liste de sites de qualité enregistrés dans la rubrique '.broken_html ($c['titre']).' du site '.$titre_annuaire.'" />'."\n";
		$head.= '<link rel="canonical" href="'.$c['info']['permanlink'].'" />'."\n";
		$head.= '<link rel="amphtml" href="'.$c['info']['permanlink'].'?amp=1" />'."\n";

		
	}
	
	//Page
	elseif($_GET['act']=='4'){
	
		$id_page = $_GET['id'];
		
		$page = rcp_page($id_page, $order);
			
		$p = $page[0];
			
		update_c (4, $p['id_page'], $p['compteur']);
				
		$head.= '<title>'.broken_html ($p['titre']).' - '.$titre_annuaire.'</title>'."\n";
		$head.= '<meta name="description" content="'.broken_html(cut_str ($p['contenu'],220)).'" />'."\n";
		$head.= '<link rel="canonical" href="'.$p['info']['permanlink'].'" />'."\n";
		$head.= '<link rel="amphtml" href="'.$p['info']['permanlink'].'?amp=1" />'."\n";
		
	}
	//Ajouter
	elseif($_GET['act']=='ad') {
	
		$head.= '<title>🔥 Ajouter un site sur l\'annuaire '.$titre_annuaire.'</title>'."\n";
		$head.= '<meta name="description" content="🔥 Ajoutez vite votre site sur l\'annuaire '.$titre_annuaire.', annuaire de qualité avec lien do-follow !" />'."\n";
		$head.= '<link rel="canonical" href="'.$url_annuaire.'ajouter.html" />'."\n";
		
	}
	
	//Résultat de l'ajout
	elseif($_GET['act']=='aj') {
	
		$head.= '<title>🔥 Ajouter un site sur l\'annuaire '.$titre_annuaire.'</title>'."\n";
		$head.= '<meta name="description" content="" />'."\n";
		$head.= '<link rel="canonical" href="'.$url_annuaire.'ajouter2.html" />'."\n";
		$head.= '<meta name="robots" content="noindex, nofollow">'."\n";
		
	}
	
	//Contact
	elseif($_GET['act']=='co') {
	
		$head.= '<title>Contacter le responsable du site '.$titre_annuaire.'</title>'."\n";
		$head.= '<meta name="description" content="Contactez-nous !" />'."\n";
		$head.= '<link rel="canonical" href="'.$url_annuaire.'contact.html" />'."\n";
		$head.= '<meta name="robots" content="noindex, nofollow">'."\n";
		
	}
	
	//Contact (Traitement)
	elseif($_GET['act']=='co') {
	
		$head.= '<title>Traitement de votre message</title>'."\n";
		$head.= '<meta name="description" content="Contactez-nous !" />'."\n";
		$head.= '<link rel="canonical" href="'.$url_annuaire.'contact2.html" />'."\n";
		$head.= '<meta name="robots" content="noindex, nofollow">'."\n";
		
	}
	
	//Acceler (Page de Paiement)
	elseif($_GET['act']=='accelerer') {
	
		$head.= '<title>Accélérer la validation de mon site sur '.$titre_annuaire.'</title>'."\n";
		$head.= '<meta name="description" content="Accelerez la validation !" />'."\n";
		$head.= '<link rel="canonical" href="'.$url_annuaire.'accelerer.html" />'."\n";
		$head.= '<meta name="robots" content="noindex, nofollow">'."\n";
		
	}
	
	//Merci (Paiement OK)
	elseif($_GET['act']=='merci') {
	
		$head.= '<title>Merci pour votre commande</title>'."\n";
		$head.= '<meta name="description" content="Paiement OK !" />'."\n";
		$head.= '<link rel="canonical" href="'.$url_annuaire.'merci.html" />'."\n";
		$head.= '<meta name="robots" content="noindex, nofollow">'."\n";
		
	}
	
	//Paramétrer mes sites (Mode PRO)
	elseif($_GET['act']=='parampro') {
	
		$head.= '<title>Paraméter mes sites enregistrés sur '.$titre_annuaire.'</title>'."\n";
		$head.= '<link rel="canonical" href="'.$url_annuaire.'parametrer.html" />'."\n";
		$head.= '<meta name="robots" content="noindex, nofollow">'."\n";
		
	}
	
	//Recharger des crédits (Mode PRO)
	elseif($_GET['act']=='recharger') {
	
		$head.= '<title>Recharger des crédits sur '.$titre_annuaire.'</title>'."\n";
		$head.= '<link rel="canonical" href="'.$url_annuaire.'recharger.html" />'."\n";
		$head.= '<meta name="robots" content="noindex, nofollow">'."\n";
		
	}
	
	//Upgrade (Mode PRO)
	elseif($_GET['act']=='selfpro') {
	
		$head.= '<title>Upgrade de votre site dans le classement '.$titre_annuaire.'</title>'."\n";
		$head.= '<link rel="canonical" href="'.$url_annuaire.'boostmywebsite.html" />'."\n";
		$head.= '<meta name="robots" content="noindex, nofollow">'."\n";
		
	}
	
	//Update (Mode PRO)
	elseif($_GET['act']=='selfproupdate') {
	
		$head.= '<title>Modification de ma fiche site sur '.$titre_annuaire.'</title>'."\n";
		$head.= '<link rel="canonical" href="'.$url_annuaire.'updatemywebsite.html" />'."\n";
		$head.= '<meta name="robots" content="noindex, nofollow">'."\n";
		
	}
	
	//Update Traitement(Mode PRO)
	elseif($_GET['act']=='selfproupdated') {
	
		$head.= '<title>Traitement de votre demande de modification</title>'."\n";
		$head.= '<link rel="canonical" href="'.$url_annuaire.'websiteupdated.html" />'."\n";
		$head.= '<meta name="robots" content="noindex, nofollow">'."\n";
		
	}
 
}
else {
	
		if (empty($hp_metatitle)) {$hp_metatitle = "$titre_annuaire ~ $description_annuaire";} 
		if (empty($hp_metadesc)) {$hp_metadesc = broken_html ($description_annuaire);} 
		
		$head.= '<title>'.$hp_metatitle.'</title>'."\n";
		$head.= '<meta name="description" content="'.$hp_metadesc.'" />'."\n";
		$head.= '<link rel="canonical" href="'.$url_annuaire.'" />'."\n";
		$head.= '<link rel="amphtml" href="'.$url_annuaire.'?amp=1" />'."\n";
}	
		
  return $head;
}

function copyright() {
	global $titre_annuaire;
	$annee = date('Y');
	$copyright = "<div id='copyright'><p>© Copyright $titre_annuaire $annee <br />Propulsé par <a href='https://janembart.com' target='_blank' rel='noopener'>Janembart X Bartemis</a>©  - <a href='https://www.robothumb.com' target='_blank' rel='noopener'>Screenshots par Robothumb</a></p></div>";
	
	return $copyright;
}


function url_www ($url) {
	$url = str_replace("https://", "", $url);
	$url = str_replace("http://", "", $url);
	$url = str_replace("www.", "", $url);
	if (substr($url, -1) == '/') {
	$url = substr($url,0,strlen($url)-1);
	}
	return $url;
}

function vider_cache($dossier) {
	

	$repertoire = opendir($dossier);
 
	while (false !== ($file = readdir($repertoire))) {
	
		$way = $dossier.'/'.$file;
 
		if ($file != '..' AND $file != '.' AND !is_dir($file))  {
		
			unlink($way);
       
	   }
}

	closedir($repertoire);

}

function valid_mail($email){
	
	$format_email='#^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,6}$#';
	
	if(preg_match($format_email,$email)) {
      
		$domaine_extrait=preg_replace('!^[a-z0-9._-]+@(.+)$!', '$1', $email);
		
		include_once dirname(__FILE__).'/email_blacklist.php';
		
		if (in_array($domaine_extrait, $domaine_bloques)) {
			return 1;
		}
		
		elseif (in_array($email, $emailblackliste)) {
			return 1;
		}
		
		else {
			return 0;
		}
	
	} else { return 1; }
	
}

function spot_relou($email){
	
	include_once dirname(__FILE__).'/email_blacklist.php';
		
		if (in_array($email, $email_colimateur)) {
			return "⚠️";
		}
	
}


function convert_br($texte, $sens) {

	if(intval($sens) == 1)  {
		$text = str_replace("<br />", CHR(10), $texte);
		
	} else {
		$text = nl2br($texte);
	}
		
	return $text;

}

function mail_inscription_accepte() {

	global $_POST, $s, $titre_annuaire, $url_annuaire, $mail;
	$contenu = file_get_contents((dirname(__FILE__).'/../config/mail_inscription_accepte.php'));
	
	$contenu = str_replace('%url_annuaire%', $url_annuaire, $contenu);
	$contenu = str_replace('%titre_annuaire%', stripslashes($titre_annuaire), $contenu);
	$contenu = str_replace('%titre_site%', stripslashes($s['titre']), $contenu);
	$contenu = str_replace('%description%', stripslashes($s['description']), $contenu);
	if ($_POST['type'] == 1) { // Si fiche site
		$elpermalinkos = $s['info']['permanlink'];
		$elpermalinkos = "<br />🤓 URL votre fiche site : <a href='$elpermalinkos'>$elpermalinkos</a>";
		$contenu = str_replace('%urlfichesite%', $elpermalinkos, $contenu);
	} else { // Si pas de fiche site
		$contenu = str_replace('%urlfichesite%', '', $contenu);
	}
	
	if(mail(trim($s['mail_auteur']),  '🔥 Votre Site Accepté sur '.$titre_annuaire.'', stripslashes($contenu), 'From: "Annuaire '.$titre_annuaire.'"<'.$mail.'>'."\n".'Reply-To: '.$mail."\n".'Content-Type: text/html; charset="utf-8"'."\n".'Mime-Version: 1.0'))
		return true;
	else
		return false;
}

function mail_inscription_refuse() {

	global $_POST, $s, $titre_annuaire, $url_annuaire, $mail;
	
	$contenu = file_get_contents((dirname(__FILE__).'/../config/mail_inscription_refuse.php'));
	
	if ($_POST['motif'] == "") {$motif="Ne réponds pas aux critères de validation";} else {$motif = $_POST['motif'];}
	$contenu = str_replace('%url_annuaire%', $url_annuaire, $contenu);
	$contenu = str_replace('%motif%', $motif, $contenu);
	$contenu = str_replace('%titre_annuaire%', stripslashes($titre_annuaire), $contenu);
	$contenu = str_replace('%titre_site%', stripslashes($s['titre']), $contenu);
	$contenu = str_replace('%description%', stripslashes($s['description']), $contenu);
	
	if(mail(trim($s['mail_auteur']),  'Refus de votre site sur '.$titre_annuaire.'', stripslashes($contenu), 'From: "Annuaire '.$titre_annuaire.'"<'.$mail.'>'."\n".'Reply-To: '.$mail."\n".'Content-Type: text/html; charset="utf-8"'."\n".'Mime-Version: 1.0'))
		return true;
	else
		return false;
}

function getHost($Address) {
	   $parseUrl = parse_url(trim($Address));
	   return trim($parseUrl['host'] ? $parseUrl['host'] : array_shift(explode('/', $parseUrl['path'], 2)));
}

function flux_rss() {

	global $_GET, $titre_annuaire, $url_annuaire, $description_annuaire;

	if ($_GET['flux'] == 'rss'){

	$rss = '<rss version="2.0">
		<channel>
		
			<title>Annuaire '.$titre_annuaire.'</title>
			<link>'.$url_annuaire.'</link>
			<description>'.$description_annuaire.'</description>';
		
		$site = rcp_site("", "", "ORDER BY id_site DESC", 1, 1, "LIMIT 10");
		
			foreach($site as $s){
				
				$date = $s['f_date'].' GMT';
				
				$sect = rcp_sect($s['sect'], "", "");
				
					foreach($sect as $se){
						
						$cat = rcp_cat($se['id_cat'], "");
						
							foreach($cat as $c){
		
							$rss.='
							<item>
							
								<title>Inscription de '.broken_html ($s['titre']).' sur '.$titre_annuaire.'</title>
								<link>'.$s['info']['permanlink'].'</link>
								<guid isPermaLink="true">'.$s['info']['permanlink'].'</guid>
								<description>Inscription du site '.broken_html ($s['titre']).' que l\'on trouvera grâce à l\'URL '.url_www ($s['url']).'. Ce site est classé dans la section '.broken_html ($se['titre']).' de la catégorie '.broken_html ($c['titre']).' de l\'annuaire '.$titre_annuaire.'</description>
								<pubDate>'.$date.'</pubDate>
								
							</item>';
							
							}
		
					}
				
			}
			
		$rss.='</channel></rss>';
		
		return str_replace(array('	', CHR(10)), '', $rss); // Le str_replace c'est pour compressé un peu le truc ;-)
	
	}
	
}