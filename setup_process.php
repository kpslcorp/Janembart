<?php 

$title="Installation en cours...";
include ('gestion/head.php');
			
$mail = $_POST["email"];
$titre_annuaire = addslashes($_POST["titre_annuaire"]);
$description_annuaire = addslashes($_POST["description_annuaire"]);
$google_analytics = $_POST["google_analytics"];
$nb_cara_description = $_POST["nb_cara_description"];
$msg2modo = $_POST["msg2modo"];
$sort4site = $_POST["sort4site"];
$forcebacklink = $_POST["forcebacklink"];
$hp_metatitle = addslashes($_POST["hp_metatitle"]);
$hp_metadesc = addslashes($_POST["hp_metadesc"]);
$nb_fiche_section = $_POST["nb_fiche_section"];
$way_of_the_fight = getcwd();
$extension = ".html";
$version="3.1.1";

// Detection du protocole (y compris via Flexible SSL de Cloudflare)
$rooturl ="";
if(!empty($_SERVER['HTTP_X_FORWARDED_PROTO'])){
    $rooturl .= $_SERVER['HTTP_X_FORWARDED_PROTO'].'://';
}
else{
    $rooturl .= !empty($_SERVER['HTTPS']) ? "https://" : "http://";
}

// Detecte l'url et retire la dernière partie
$url = $_SERVER['REQUEST_URI']; //returns the current URL
$parts = explode('/',$url);
$dir = $_SERVER['SERVER_NAME'];
for ($i = 0; $i < count($parts) - 1; $i++) {
 $dir .= $parts[$i] . "/";
}

// Detection du WWW
$www = "";
if(strpos($_SERVER['HTTP_HOST'], 'www') !== false) { // Detection du WWW
   $www = "www.";
} 

$url_annuaire = $rooturl . $www. $dir; // Fusion
$url_annuaire = str_replace("www.www.","www.", $url_annuaire); // Selon les hébergeurs, correction du bug du double www.

//// Verification de la précence su slash
$slashornot = substr("$url_annuaire", -1);
if ($slashornot != "/") {$url_annuaire = "$url_annuaire/";}
///////////////////////////////////////	

// SQL
$serveur = trim($_POST["serveur"]);
$nom_utilisateur = trim($_POST["nom_utilisateur"]);
$pass = trim($_POST["pass"]); // SQL Password
$base_de_donnee = trim($_POST["base_de_donnee"]);;
/// GESTION DU PREFIXE PREFIXE ////////////
$prefixe_sql = trim($_POST["prefixe_sql"]);
$smartsqlprefixe = $prefixe_sql;
$smartsqlprefixe .= strlen($smartsqlprefixe);
$smartsqlprefixe .= "_";
////////////////////////////////////////
// SQL

/// Generation du Pepper ////
function generateRandomString($length = 30) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
$pepper = generateRandomString();
////////////////////////////

// Cryptage du PWD Admin
$pwd = $_POST["password"];
$pwd_peppered = hash_hmac("sha256", $pwd, $pepper);
$password = password_hash($pwd_peppered, PASSWORD_DEFAULT);
////////////////////////

$xfiles = "$way_of_the_fight/config.php"; // Construction du nom du fichier
		
		// Création de la base MYSQL
		try
		{
			$connexion = mysqli_connect($serveur, $nom_utilisateur, $pass, $base_de_donnee);
			mysqli_set_charset($connexion, "utf8");
			if (mysqli_connect_errno()) {
				// echo "Failed to connect to MySQL: " . mysqli_connect_error(); // DEBUG MODE
				exit ("<p>Erreur de connexion à votre base de donnée.<br /> Merci de cliquer sur RETOUR et de vérifier les informations saisies.</p><p style='text-align:center;margin-top:60px;'><a href='javascript:history.back()' class='form__submit'>Retour</a></p>");
			}
		}
		catch(Exception $e)
		{
			 die('Erreur : '.$e->getMessage());
		}
	
		$prefixe_site = $smartsqlprefixe."site";
		$prefixe_cat = $smartsqlprefixe."cat";
		$prefixe_page = $smartsqlprefixe."page";
		$prefixe_sect = $smartsqlprefixe."sect";
		$prefixe_reset = $smartsqlprefixe."reset";
		$prefixe_u = $smartsqlprefixe."u";
		$prefixe_relance = $smartsqlprefixe."relance";
		
		
		$mlegaledebaz = "<p>Le site $titre_annuaire ($url_annuaire) est créé et géré par __________ domicilié _____________</p><h2>Script</h2><p>Cet annuaire s'appuie sur le script <a href='https://janembart.com/' title='script annuaire php gratuit'>Janembart</a> ©, qui est une adaptation du script Bartemis ©</p><h2>Politique de Confidentialité</h2><p>Votre politique de confidentialité ___________</p><h2>Hébergeur</h2><p>Nom et adresse de l'hébergeur_______</p><h2>Directeur de publication</h2><p>Votre Nom et Prénom</p>";
		$mlegaledebaz = mysqli_real_escape_string($connexion,$mlegaledebaz);
		

		$sql = "CREATE TABLE IF NOT EXISTS `$prefixe_site` (
		  `id_site` int(11) unsigned NOT NULL auto_increment,
		  `sect` int(11) NOT NULL,
		  `type` int(11) NOT NULL,
		  `titre` varchar(255) NOT NULL default '',
		  `url` text NOT NULL,
		  `ancre` varchar(255) NOT NULL,
		  `url_retour` text NOT NULL,
		  `url_rss` text NOT NULL,
		  `description` mediumtext NOT NULL,
		  `mail_auteur` varchar(255) NOT NULL default '',
		  `valide` int(11) unsigned NOT NULL default '0',
		  `compteur` int(11) unsigned NOT NULL default '2',
		  `note` int(11) unsigned NOT NULL default '2',
		  `dat` datetime NOT NULL default '0000-00-00 00:00:00',
		  `date2validation` datetime NULL,
		PRIMARY KEY ( `id_site` )) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci;

		CREATE TABLE IF NOT EXISTS `$prefixe_cat` (
		  `id_cat` int(11) unsigned NOT NULL auto_increment,
		  `titre` varchar(255) NOT NULL default '',
		  `description` mediumtext NOT NULL,
		  `compteur` int(11) unsigned NOT NULL default '2',
		PRIMARY KEY ( `id_cat` )) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci;

		CREATE TABLE IF NOT EXISTS `$prefixe_page` (
		  `id_page` int(11) unsigned NOT NULL auto_increment,
		  `titre` varchar(255) NOT NULL default '',
		  `contenu` mediumtext NOT NULL,
		  `compteur` int(11) unsigned NOT NULL default '2',
		PRIMARY KEY ( `id_page` )) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci;
		
		INSERT INTO $prefixe_page(titre,contenu,compteur) VALUES ('Mentions Légales', '$mlegaledebaz', '1');

		CREATE TABLE IF NOT EXISTS `$prefixe_sect` (
		  `id_sect` int(11) unsigned NOT NULL auto_increment,
		  `titre` varchar(255) NOT NULL default '',
		  `id_cat` int(11) NOT NULL default 0,
		  `description` mediumtext NOT NULL,
		  `compteur` int(11) unsigned NOT NULL default '2',
		PRIMARY KEY ( `id_sect` )) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci;
		
		CREATE TABLE IF NOT EXISTS `$prefixe_reset` (
		  `id` int(11) unsigned NOT NULL auto_increment,
		  `email` varchar(255) NOT NULL default '',
		  `token` varchar(255) NOT NULL default 0,
		  `salledutemps` datetime NOT NULL,
		UNIQUE KEY token (`token`),
		PRIMARY KEY ( `id` )) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci;
		
		CREATE TABLE IF NOT EXISTS `$prefixe_u` (
		  `id_user` int(11) unsigned NOT NULL auto_increment,
		  `mail` varchar(100) CHARACTER SET utf8 NOT NULL ,
		  `password` varchar(255) CHARACTER SET utf8 NOT NULL,
		  `statut` varchar(30) CHARACTER SET utf8 NOT NULL,
		  `credit` int(11) NOT NULL,
		  `creation` datetime NOT NULL default '0000-00-00 00:00:00',
		  `valide` int(11) NOT NULL default 0,
		  `tekken3` varchar(255) CHARACTER SET utf8 NOT NULL,
		UNIQUE KEY mail (`mail`),
		PRIMARY KEY ( `id_user` )) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci;
		ALTER TABLE `$prefixe_u` AUTO_INCREMENT=2;

		INSERT INTO $prefixe_u(mail,password,statut,credit,valide) VALUES ('$mail', '$password', 'kaioh', '666','1');
		
		CREATE TABLE IF NOT EXISTS `$prefixe_relance` (
			`id_ticket` int(11) unsigned NOT NULL auto_increment,
			`id_site` int(11) unsigned NOT NULL,
			`date_ticket` date NOT NULL,
		UNIQUE KEY id_ticket (`id_ticket`),
		PRIMARY KEY ( `id_ticket` )) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci;
		";

		if(mysqli_multi_query($connexion, $sql)){
			
			echo "<h1>🎉 Bien joué ! Janembart est installé 🎉</h1><p>Pour administrer votre annuaire :</p><ol><li>Cliquez ci-dessous sur le gros bouton SE CONNECTER A L'ADMIN</li><li>Saisissez votre adresse mail : ($mail)</li><li>Saisissez le mot de passe que vous avez renseigné lors de l'installation</li></ol><p>🤐 Vous pouvez modifier pas mal de paramétrages depuis le fichier config.php situé à la racine du site</p><p>🚀 Pensez à inscrire votre site sur notre <a href='https://janembart.com'>annuaire d'annuaires Janembart</a></p><p style='text-align:center;margin-top:60px;'><a href='gestion/login.php' class='form__submit'>Se connecter à l'admin</a></p>";
			
			
			$destinataire = $mail;
			$sujet = "🔥 Installation de mon annuaire $titre_annuaire";
			$headers = 'Mime-Version: 1.0'."\r\n";
			$headers .= 'Content-type: text/html; charset=utf-8'."\r\n";
			$headers .= "From: $titre_annuaire <$mail>"."\r\n";
			$lagestion = $url_annuaire."gestion";
			$message = "<h1>✨ WHAOU ! Janembart a été installé avec succès ✨</h1><p>Pour administrer votre annuaire :</p><ol><li>Rendez-vous sur cette url ➡️<a href='$lagestion'>$lagestion</a></li><li>Saisissez votre adresse mail : $mail</li><li>Saisissez le mot de passe que vous avez renseigné lors de l'installation 🤐</li></ol><p>💡 Bon à savoir : Vous pouvez modifier pas mal de paramétrages depuis le fichier config.php situé à la racine du site</p><p>🚀 Pensez à inscrire votre site sur notre <a href='https://janembart.com'>annuaire d'annuaires Janembart</a></p>";
			mail($destinataire, $sujet, $message, $headers);
			
			// Creation du fichier config.php
			
				if (!file_exists($xfiles)) { // S'il n'existe pas déjà 

				// Construction du fichier config
				@mkdir( $way_of_the_fight, 0755 );
				$smartimprovment = "<?php"."\n";
				
				$smartimprovment .= "// Données sur votre annuaire "."\n";
				$smartimprovment .= "\$url_annuaire = '$url_annuaire'; // Attention à ce que cela reflete parfaitement l'url où est installé votre annuaire. Y-a-t-il bien le slash (/) à la fin de l'url ? Le www. ou pas ? Le https://..."."\n";
				$smartimprovment .= "\$titre_annuaire = '$titre_annuaire';"."\n";
				$smartimprovment .= "\$description_annuaire = '$description_annuaire'; // Punchline, n'écrivez pas un roman !"."\n";
				$smartimprovment .= "\$mail = '$mail';"."\n";
				$smartimprovment .= "\$extension = '$extension';"."\n";
				$smartimprovment .= "\$version = '$version';"."\n\n";
				$smartimprovment .= "// Données Mysql"."\n";
				$smartimprovment .= "\$serveur = '$serveur'; // Ex : localhost"."\n"; 
				$smartimprovment .= "\$nom_utilisateur = '$nom_utilisateur'; // Nom utilisateur SQL"."\n";
				$smartimprovment .= "\$pass = '$pass'; // Mot de passe SQL"."\n";
				$smartimprovment .= "\$base_de_donnee = '$base_de_donnee'; // Nom de la BDD"."\n";
				$smartimprovment .= "\$pepper = '$pepper'; // SALT User Password // Ne pas touchew"."\n";
				$smartimprovment .= "define('SQL_PREFIXE','$smartsqlprefixe'); // Préfixe des tables de la BDD"."\n";
				$smartimprovment .= "define('BATBASE','$way_of_the_fight');"."\n\n";
				$smartimprovment .= "// Configurations Annexes"."\n";
				$smartimprovment .= "\$disable_amp = 'non'; // 'oui' si vous souhaitez désactiver AMP / laissez vide pour laisser activé une version AMP"."\n";
				$smartimprovment .= "\$google_analytics = '$google_analytics'; // Laissez vide si vous n'utilisez pas Google Analytics"."\n";
				$smartimprovment .= "\$nb_cara_description = '$nb_cara_description'; // Nombre de caractères minimum exigés pour la description des sites soumis"."\n";
				$smartimprovment .= "\$forcebacklink = '$forcebacklink'; // «oui» = Lien retour obligatoire | «non» = Lien retour faculatif "."\n";
				$smartimprovment .= "\$sort4site = '$sort4site'; // «new2old» = Nouveaux > Anciens | «old2new» = Anciens > Nouveau | «note» = Note donnée par le modérateur | «az» = Ordre alphabétique "."\n";
				$smartimprovment .= "\$nb_fiche_section = '$nb_fiche_section'; // Nombre de fiches sites affichées dans chaque pages sections"."\n";
				$smartimprovment .= "\$msg2modo = '$msg2modo'; // «all» = Recevez un mail pour chaque nouvelle inscription | «spoted» = Recevez un mail pour chaque nouvelle inscription avec lien retour | «» = Ne pas recevoir de mail"."\n\n";
				$smartimprovment .= "// Configurations Paypal"."\n";
				$smartimprovment .= "\$paypal_id = ''; // Saisissez ici l'ID du module de paiement Paypal Fast Checkout"."\n";
				$smartimprovment .= "\$mode_vacances = TRUE; // TRUE désactive le module de paiement Paypal, FALSE active le module Paypal si paramétré"."\n\n";
				$smartimprovment .= "// Homepage SEO"."\n";
				$smartimprovment .= "\$hp_metatitle = '$hp_metatitle'; // Sera utilisé pour remplir la balise <title> de la page d'accueil de votre annuaire | + d'infos sur https://www.balisemeta.com | Laissez vide pour utiliser la structure par défaut"."\n";
				$smartimprovment .= "\$hp_metadesc = '$hp_metadesc'; //  Sera utilisé pour remplir la balise <meta description> de la page d'accueil de votre annuaire | + d'infos sur https://www.balisemeta.com | Laissez vide pour utiliser la structure par défaut"."\n\n";
				$smartimprovment .= "// Hompepage Spotlight"."\n";
				$smartimprovment .= "/// URL des sites que vous voulez mettre en avant (contre $$$ ?) ==> Ne nécessite pas d'avoir une fiche site enregistrée sur l'annuaire / Variable vide si vous ne voulez pas de ce module"."\n";
				$smartimprovment .= "\$coupdecoeur = array('https://janembart.com','https://www.balisemeta.com','https://meilleur-hebergeur.org/','https://www.w3.org/','https://jquery.com/','https://www.bitdefender.fr/');"."\n";
				$smartimprovment .= "/// ID des sites que vous voulez mettre en avant (contre $$$ ?) ==> Nécessite d'avoir une fiche site enregistrée sur l'annuaire (Bah ouais, logique puisqu'il faut bien une ID) / Variable vide si vous ne voulez pas de ce module"."\n";
				$smartimprovment .= "\$incontournables = array(1,2,3,4,5,6);"."\n";

				$smartimprovment .= " ?>";
				file_put_contents($xfiles , $smartimprovment); // Création du fichier
				
				//Création de l'URL Rewriting
				$f = fopen(".htaccess", "a+");
				
				$domaini = $_SERVER['SERVER_NAME'];
				$enmadaioh = $url_annuaire;
				$enmadaioh = str_replace("https://", "", $enmadaioh);
				$enmadaioh = str_replace("http://", "", $enmadaioh);
				$enmadaioh = str_replace("www.", "", $enmadaioh);
				$enmadaioh = str_replace($domaini, "", $enmadaioh);
				
				// génération du Htaccess
				$htaccess2k ="\n\n";
				$htaccess2k .="# A modifier si votre annuaire n'est pas à la racine de votre site"."\n";
				$htaccess2k .="# Exemple 1 : si votre annuaire se trouve à l'adresse $domaini/annuaire vous devrez avoir pour RewriteBase : /annuaire/"."\n";
				$htaccess2k .="# Exemple 2 : si votre annuaire se trouve à l'adresse $domaini vous devrez avoir pour RewriteBase : /"."\n";
				$htaccess2k .="RewriteBase $enmadaioh"."\n\n";

				$htaccess2k .="RewriteRule ^ajouter.html index.php?act=ad [QSA,L]"."\n";
				$htaccess2k .="RewriteRule ^ajouter2.html index.php?act=aj [QSA,L]"."\n";
				$htaccess2k .="RewriteRule ^contact.html index.php?act=co [QSA,L]"."\n";
				$htaccess2k .="RewriteRule ^contact2.html index.php?act=coj [QSA,L]"."\n";
				$htaccess2k .="RewriteRule ^parametrer.html index.php?act=parampro [QSA,L]"."\n";
				$htaccess2k .="RewriteRule ^recharger.html index.php?act=recharger [QSA,L]"."\n";
				$htaccess2k .="RewriteRule ^accelerer.html index.php?act=accelerer [QSA,L]"."\n";
				$htaccess2k .="RewriteRule ^merci.html index.php?act=merci [QSA,L]"."\n";
				$htaccess2k .="RewriteRule ^boostmywebsite.html index.php?act=selfpro [QSA,L]"."\n";
				$htaccess2k .="RewriteRule ^updatemywebsite.html index.php?act=selfproupdate [QSA,L]"."\n";
				$htaccess2k .="RewriteRule ^websiteupdated.html index.php?act=selfproupdated [QSA,L]"."\n\n";
				$htaccess2k .="RewriteRule ^search.html index.php?act=search [QSA,L]"."\n\n";
				$htaccess2k .="RewriteRule ^index-amp.html index.php?amp=1 [QSA,L]"."\n\n";
				
				$htaccess2k .="RewriteRule ^([a-z0-9\-]+)/([a-z0-9\-]+)/([0-9]+).([0-9]+)-([a-z0-9\-]+)-amp$extension index.php?cat=$1&sect=$2&act=$3&id=$4&titre=$5&amp=1 [QSA,L]"."\n";
				$htaccess2k .="RewriteRule ^([a-z0-9\-]+)/([a-z0-9\-]+)/([0-9]+).([0-9]+)-([a-z0-9\-]+)$extension index.php?cat=$1&sect=$2&act=$3&id=$4&titre=$5 [QSA,L]"."\n";
				$htaccess2k .="RewriteRule ^([a-z0-9\-]+)/([0-9]+).([0-9]+)-([a-z0-9\-]+)-amp$extension index.php?cat=$1&act=$2&id=$3&sect=$4&amp=1 [QSA,L]"."\n";
				$htaccess2k .="RewriteRule ^([a-z0-9\-]+)/([0-9]+).([0-9]+)-([a-z0-9\-]+)$extension index.php?cat=$1&act=$2&id=$3&sect=$4 [QSA,L]"."\n";
				$htaccess2k .="RewriteRule ^([0-9]+).([0-9]+)-([a-z0-9\-]+)-amp$extension index.php?act=$1&id=$2&cat=$3&amp=1 [QSA,L]"."\n\n";
				$htaccess2k .="RewriteRule ^([0-9]+).([0-9]+)-([a-z0-9\-]+)$extension index.php?act=$1&id=$2&cat=$3 [QSA,L]"."\n\n";

				$htaccess2k .="#Flux RSS"."\n";
				$htaccess2k .="RewriteRule ^sitemap/([a-z0-9\-]+)-([0-9]+).([a-z0-9\-]+).* index.php?act=$1&c=$2&format=$3 [QSA,L]"."\n";
				$htaccess2k .="RewriteRule ^([a-z0-9\-]+)/([a-z0-9\-]+).xml index.php?act=$1&flux=$2 [QSA,L]"."\n";
				$htaccess2k .="RewriteCond %{REQUEST_FILENAME} !-f"."\n";
				$htaccess2k .="RewriteCond %{REQUEST_FILENAME} !-d"."\n";
				$htaccess2k .="RewriteRule (.*) erreur.php";

				fwrite($f, $htaccess2k);
				fclose($f);
				
			$autodestruction = $way_of_the_fight.'/install.php';
			$autodestruction2 = $way_of_the_fight.'/setup_process.php';
			unlink($autodestruction);
			unlink($autodestruction2);

		}

			
		} else {
			
			// echo "Erreur : $sql. " . mysqli_error($connexion); // DEBUG MODE
			echo "<p>Erreur lors de la création de vos tables SQL.<br /> Veuillez <a href='https://janembart.com/annuaire/contact.html'>contacter le support de Janmebart</a></p>";
			echo'<p style="text-align:center;margin-top:60px;"><a href="javascript:history.back()" class="form__submit">Retour</a></p>';
			
		}

	// Close connection
		mysqli_close($connexion);
 	

include ('gestion/foot.php');

?>