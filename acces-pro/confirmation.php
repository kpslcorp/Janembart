<?php 
require(dirname(__FILE__).'/../config.php');

function valid_donnees($donnees){
        $donnees = trim($donnees);
        $donnees = stripslashes($donnees);
        $donnees = htmlspecialchars($donnees);
        return $donnees;
    }
	
$jin = valid_donnees($_GET["jin"]);
$kazama = valid_donnees($_GET["kazama"]);
$userbaz = SQL_PREFIXE.'u';


if ((empty($jin)) OR (empty($kazama))) { // On vérifie si il y a bien un token

	exit('Ouch');
	
}



else { // Si les 2 champs sont remplis :

	try
	{
	$bdd = new PDO("mysql:host=$serveur;dbname=$base_de_donnee;charset=utf8", "$nom_utilisateur", "$pass", array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));	
	$bdd->exec("set names utf8");
	}
	catch(Exception $e)
	{
       exit('Ouch');
	}
	
// On vérifie déja si l'email existe
$check_if_mail_exist = $bdd->prepare("SELECT * FROM $userbaz WHERE id_user = :id_user");
$check_if_mail_exist->bindParam(':id_user', $kazama);
$check_if_mail_exist->execute();
$resultatquest=$check_if_mail_exist->rowCount();

if ($resultatquest > 0) // Si l'email existe on vérifie le mot de passe
	{

		while ($donnees = $check_if_mail_exist->fetch()) // On récupère les données liées à l'email
		{
			$mail_user = $donnees['mail'];
			$tekken = $donnees['tekken3'];
			$valide = $donnees['valide'];
		}

		
		if ($valide != 1) { // Si ce n'est pas déja validé
		
			if ($tekken == $jin) { // Si ca match => Verification success!
				
				try
				{
					$oklm = $bdd->prepare("UPDATE $userbaz SET valide=1 WHERE id_user = :id_user");
					$oklm->bindParam(':id_user', $kazama);
					$oklm->execute();
					$title = "Compte pro confirmé";
					include ('../gestion/head.php');
					echo "<h2>✅ Merci</h2><p style='text-align:center;'>Votre compte pro a bien été confirmé.</p><p style='text-align:center;'>⭐ Pour vous connecter à votre Espace PRO, rendez-vous sur cette page <a href='$urldulogin'>$urldulogin</a> et pensez à mettre cette url dans vos favoris ⭐</p>";
					
					$urldulogin = $url_annuaire.'acces-pro/login.php';
					
					$message_du_mail = "<div style='margin:0 auto;width:80%;'><h2 style='text-align:center;'>✅ Votre compte PRO est confirmé</h2>
					<p style='text-align:center;'>BRAVO ! Votre compte pro vient d'être confirmé.</p>
					<p style='text-align:center;'>⭐ Pour vous connecter à votre Espace PRO, rendez-vous sur cette page : <a href='$urldulogin'>$urldulogin</a> et pensez à la mettre dans vos favoris.</p></div>";
					
					$destinataire = $mail_user;
					$sujet = "⭐ (2/2) Validation de votre Compte PRO sur $titre_annuaire ⭐";
					$headers = 'Mime-Version: 1.0'."\r\n";
					$headers .= 'Content-type: text/html; charset=utf-8'."\r\n";
					$headers .= "From: $titre_annuaire <$mail>"."\r\n";
					
					mail($destinataire, $sujet, $message_du_mail, $headers);
				}
				
				catch(Exception $e)
				{
					exit('Ouch');
				}	
	
			} 
			
			else {
				$title = "Erreur";
				include ('../gestion/head.php');	
				echo '<h2>Impossible de valider ce compte</h2>'; // Le token ne correspond pas à la boite mail mais on reste vague pour ne pas donner de piste
				exit();
			}
		
		} else {
			
			$title = "Compte pro déjà confirmé";
			include ('../gestion/head.php');
			$urldulogin = $url_annuaire.'acces-pro/login.php';
			echo "<h2>✅ Votre compte a déjà été confirmé</h2><p style='text-align:center;'>⭐ Pour vous connecter à votre Espace PRO, rendez-vous sur cette page <a href='$urldulogin'>$urldulogin</a> et pensez à mettre cette url dans vos favoris ⭐</p>";
			
		}
	}
	
else { // Si l'email n'existe pas on passe en erreur
		$title = "Erreur";
		include ('../gestion/head.php');	
		echo '<h2>Impossible de valider ce compte</h2>'; // Utilisateur n'existe pas mais on reste vague pour ne pas donner de piste
		exit();
	}
}
include ('../gestion/foot.php');
?>