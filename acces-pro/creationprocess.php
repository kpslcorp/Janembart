<?php
session_start();
require(dirname(__FILE__).'/../config.php');
$title="Création du compte";
include ('../gestion/head.php');
$i_user = SQL_PREFIXE.'u';


function validator($donnees){
        $donnees = trim($donnees);
        $donnees = stripslashes($donnees);
        $donnees = htmlspecialchars($donnees);
		$donnees = strip_tags($donnees);
		$donnees = str_replace('"', '', $donnees);
		$donnees = str_replace("'","&#039;",$donnees);
		$donnees = str_replace(">","&gt;",$donnees);
		$donnees = str_replace("<","&lt;",$donnees);
        return $donnees;
}

	$maildupro = validator($_POST["email"]);
	$captcha = validator($_POST["security_code"]);
	
	// Cryptage du PWD Admin
	$pwd = $_POST["password"];
	$pwd_peppered = hash_hmac("sha256", $pwd, $pepper);
	$password = password_hash($pwd_peppered, PASSWORD_DEFAULT);
	
	$token = base_convert(hash('sha256', time() . mt_rand()), 16, 36);	// Pour valider l'adresse email
	
try
			{
				$db = mysqli_connect($serveur, $nom_utilisateur, $pass, $base_de_donnee);
				mysqli_set_charset($db, "utf8");
				if (mysqli_connect_errno()) {
					echo "Failed to connect to MySQL: " . mysqli_connect_error();
				}
			}
			
			catch(Exception $e)
			{
				die('Erreur : '.$e->getMessage());
			}	

$errors = []; // you can add errors to this array.

if (isset($_POST['submit']))
	{
		
		if (empty($maildupro))
		{$errors[]= "Merci de saisir une adresse mail";}
	
		if (filter_var($maildupro, FILTER_VALIDATE_EMAIL) == false)
		{$errors[]= "Votre adresse mail est incorrecte";}
	
		if( $_SESSION['kap_tcha_id'] == $_POST['security_code'] && !empty($_SESSION['kap_tcha_id'] ) ) {} 
		else {$errors[]= 'La réponse à la question secrète est incorrecte ? Seriez-vous un bot ?'; }
		
		$mailexist = mysqli_num_rows(mysqli_query($db,"SELECT * FROM $i_user WHERE mail = '$maildupro'"));
		if ($mailexist!=0) 
		{$errors[]= "Cette adresse mail existe déjà dans notre base de donnée, merci d'en utiliser une autre.";}
	
		if (isset($_POST['cgv-u'])) // Honeypot
		{$errors[]= "Vous avez un oeil bionique pour voir ce champ ?";}
	
		if (!isset($_POST['cgv']))
		{$errors[]= "Vous devez accepter nos conditions générales de vente";}
	
		if (!isset($_POST['rpgd']))
		{$errors[]= "Vous devez accepter notre politique de confidentialité";}
		
	}
	
	else 
		
	{
		{$errors[]= "Le formulaire est vide. Que faites-vous ici ?";}
	}
	
	if (!empty($errors))
    {
		echo "<h2>Ouch !</h2><ul style='color:red;'>";
	
		foreach($errors as $valeur) 
		{  
			echo "<li>$valeur</li>";
		}
		echo "</ul><p style='text-align:center;margin-top:50px;'><a href='javascript:history.back()' class='form__submit'>Retour</a></p>";
    } 
	
	else { 
	
			$datedinscription = date("Y-m-d H:i:s");
		
			$sql = "INSERT INTO $i_user (mail,password,statut,credit,creation,valide,tekken3) VALUES ('$maildupro', '$password', 'pr0', '0','$datedinscription','2','$token')";
			$results = mysqli_query($db, $sql);
			
			$id_du_dernier_enregistrement = mysqli_insert_id($db);
			
			$urldulogin = $url_annuaire.'acces-pro/login.php';
			$urldevalidationducompte = $url_annuaire.'acces-pro/confirmation.php?jin='.$token.'&kazama='.$id_du_dernier_enregistrement.'';
			
			$message = "<h2 style='text-align:center;'>✅ Confirmez maintenant votre compte PRO ✅</h2>
			<p style='text-align:center;'>BRAVO ! Votre compte pro vient d'être créé avec succès mais <u><strong>reste en attente de validation</strong></u>.</p>
			<h2 style='margin:40px;'>Que faire maintenant ?</h2>
			<p style='text-align:center;'>⚠️ IMPORTANT : Nous venons de vous envoyer un mail de confirmation. Merci de confirmer votre adresse email en <strong>cliquant sur le lien reçu sur votre boite mail</strong>.</p>
			<p style='text-align:center;'>💡 Si vous n'avez rien reçu au bout de une minute, pensez à regarder dans vos SPAMS.</p>";
			$message_du_mail = "<div style='margin:0 auto;width:80%;'><h2 style='text-align:center;'>✅ Confirmez maintenant votre compte PRO !</h2>
			<p style='text-align:center;'>BRAVO ! Votre compte pro vient d'être créé avec succès mais <u><strong>reste en attente de validation</strong></u>.</p>
			<p style='text-align:center;font-weight:bold;font-size:130%;'>Merci de confirmer votre adresse email en <a href='$urldevalidationducompte'>cliquant ici</a> ou en copiant l'URL suivante directement dans votre navigateur :<br /> $urldevalidationducompte</p>
			<h2 style='text-align:center;'>C'est quoi la suite ?</h2>
			<p>Une fois votre compte validé, vous allez être recontacté très rapidement par l'administrateur de l'annuaire dès lors qu'il aura débloqué le nombre de crédits que vous avez convenus ensemble.</p>
			<p style='text-align:center;'>⭐ Pour vous connecter à votre Espace PRO, rendez-vous sur cette page : <a href='$urldulogin'>$urldulogin</a> et pensez à la mettre dans vos favoris.</p></div>";
			
			$destinataire = $maildupro;
			$sujet = "⭐ (1/2) Création de votre Compte PRO sur $titre_annuaire ⭐";
			$headers = 'Mime-Version: 1.0'."\r\n";
			$headers .= 'Content-type: text/html; charset=utf-8'."\r\n";
			$headers .= "From: $titre_annuaire <$mail>"."\r\n";
			
			mail($destinataire, $sujet, $message_du_mail, $headers);
			
			$destinataire_admin = $mail;
			$sujet_admin = "⚠️ Création d'un nouveau compte PRO sur $titre_annuaire";
			$headers_admin = 'Mime-Version: 1.0'."\r\n";
			$headers_admin .= 'Content-type: text/html; charset=utf-8'."\r\n";
			$headers_admin .= "From: $titre_annuaire <$mail>"."\r\n";
			$message_du_mail_admin = "<p>Juste pour info : un nouveau compte pro vient d'être créé ( $maildupro )</p>";
			
			mail($destinataire_admin, $sujet_admin, $message_du_mail_admin, $headers_admin);
				
			echo $message;

			// Close connection
			mysqli_close($db);	
		

	
	}
	
			// Destruction Session
			session_destroy ();

include ('../gestion/foot.php');

?>