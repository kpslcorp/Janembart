<?php 
require(dirname(__FILE__).'/../config.php');

function valid_donnees($donnees){
        $donnees = trim($donnees);
        $donnees = stripslashes($donnees);
        $donnees = htmlspecialchars($donnees);
        return $donnees;
    }
	
$mail = valid_donnees($_POST["email"]);
$pwd = $_POST["password"];
$userbaz = SQL_PREFIXE.'u';

if ( !isset($mail, $pwd) ) { // On vérifie si les 2 champs login/pwd ne sont pas vide

	$title = "Erreur";
	include ('head.php');	
	echo('Merci de saisir votre email et mot de passe');
}


else { // Si les 2 champs sont remplis :

	
	try
	{
	$bdd = new PDO("mysql:host=$serveur;dbname=$base_de_donnee;charset=utf8", "$nom_utilisateur", "$pass", array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));	
	$bdd->exec("set names utf8");
	}
	catch(Exception $e)
	{
        die('Erreur : '.$e->getMessage());
	}
	
// On vérifie déja si l'email existe
$check_if_mail_exist = $bdd->prepare("SELECT * FROM $userbaz WHERE mail = :mail");
$check_if_mail_exist->bindParam(':mail', $mail);
$check_if_mail_exist->execute();
$resultatquest=$check_if_mail_exist->rowCount();

if ($resultatquest > 0) // Si l'email existe on vérifie d'abord si le compte est validé puis le mot de passe
	{

		while ($donnees = $check_if_mail_exist->fetch()) // On récupère les données liées à l'email
		{
			$id_user = $donnees['id_user'];
			$passql = $donnees['password'];
			$statut = $donnees['statut'];
			$valide = $donnees['valide'];
		}

		if ($valide == 1) {
			// On prépare le password
			$pwd = $_POST['password'];
			$pwd_peppered = hash_hmac("sha256", $pwd, $pepper);
			$pwd_hashed = $passql;

			// On compare le mot de pass entré et le mot de passe stockée.
			if (password_verify($pwd_peppered, $pwd_hashed)) { // Si ca match => Verification success!
				session_start(); // Ouvre la session
				
				$smarttoken = bin2hex(random_bytes(50));
				
				setcookie('admink',$smarttoken,time()+3600,'/') ;

				$_SESSION['admink'] = $smarttoken;
				$_SESSION['loggedin'] = TRUE;
				$_SESSION['statut'] = $statut;
				$_SESSION['id_user'] = $id_user;
				$_SESSION['mail_user'] = $mail;
				
				if ($statut == "kaioh") {
					$sezamouvretoi = "$url_annuaire"."gestion/index.php";
					header("Location: $sezamouvretoi");    
				} else {
					$sezamouvretoi = "$url_annuaire"."ajouter.html";
					header("Location: $sezamouvretoi");    
				}
			} 
			else {
				$title = "Erreur";
				include ('head.php');	
				echo '<h2>Erreur de connexion</h2><p style="text-align:center;margin-top:60px;">Votre adresse email et/ou votre mot de passe est incorrect</p>';
				echo '<p style="text-align:center;margin-top:60px;"><a href="javascript:history.back()" class="form__submit">Retour</a></p>';
				exit();
			}
		}
		else {
				$title = "Erreur";
				include ('head.php');	
				$contactformurl = $url_annuaire."contact.html";
				echo "<h2>Votre compte est inactif</h2><p style='text-align:center;margin-top:60px;'>Votre compte n'a pas encore été activé. Merci de consulter vos mails et de cliquer sur le lien d'activation pour valider votre compte PRO.</p><p>Si vous ne trouvez pas le mail que nous vous avons envoyé suite à votre inscription, pensez à regarder dans votre boite de courriers indésirables. Sinon <a href='$contactformurl'>contactez l'administrateur de cet annuaire</a>.</p>";
				exit();
		}
	}
	
else { // Si l'email n'existe pas on passe en erreur
		$title = "Erreur";
		include ('head.php');	
		echo '<h2>Erreur de connexion</h2><p style="text-align:center;margin-top:60px;">Votre adresse email et/ou votre mot de passe est incorrect</p>'; // Utilisateur n'existe pas mais on reste vague pour ne pas donner de piste
		echo '<p style="text-align:center;margin-top:60px;"><a href="javascript:history.back()" class="form__submit">Retour</a></p>';
		exit();
	}
}
include ('foot.php');
?>