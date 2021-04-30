<?php 

session_start();
$errors = [];


// connect to database
require(dirname(__FILE__).'/../../config.php');
$db = mysqli_connect($serveur, $nom_utilisateur, $pass, $base_de_donnee);

$i_reset = SQL_PREFIXE.'reset';
$i_user = SQL_PREFIXE.'u';

/*
  Accept email of user whose password is to be reset
  Send email to user to reset their password
*/
if (isset($_POST['reset-password'])) {
  $email = mysqli_real_escape_string($db, $_POST['email']);
  // ensure that the user exists on our system
  $query = "SELECT mail FROM $i_user WHERE mail='$email'";
  $results = mysqli_query($db, $query);

  if (empty($email)) {
    array_push($errors, "Merci de saisir une adresse mail valide");
  }else if(mysqli_num_rows($results) <= 0) {
    array_push($errors, "Un mail de réinitialisation vient d'être envoyé sur cette boite mail (si elle existe). 💡 Si vous n'avez rien reçu dans les 5 minutes (y compris dans vos spams) c'est que l'adresse email est incorrecte."); // eMail non trouvée dans la base, mais on ne le dit pas clairement pour éviter un hack
  }
  
  // generate a unique random token et on le crypte
	$token = bin2hex(random_bytes(50));
	$token_crypte = hash_hmac("sha256", $token, $pepper);

  if (count($errors) == 0) {
    // store token in the password-reset database table against the user's email
	$salledutemps = date("Y-m-d H:i:s");
    $sql = "INSERT INTO $i_reset(email, token, salledutemps) VALUES ('$email', '$token_crypte','$salledutemps')";
    $results = mysqli_query($db, $sql);

    // Send email to user with the token in a link they can click on
    $to = $email;
	
	$headers = 'Mime-Version: 1.0'."\r\n";
	$headers .= 'Content-type: text/html; charset=utf-8'."\r\n";
	$headers .= "From: $titre_annuaire <$mail>"."\r\n";
    $subject = "Demande de réinitialisation du mot de passe sur $titre_annuaire";
    $msg = "<p>Vous avez oublié votre mot de passe ? Pas de panique ! Vous allez pouvoir en créer un nouveau <a href='".$url_annuaire."config/pwdrecovery/next_passwd.php?tiktok=" . $token . "'>en cliquant ici</a>.</p><p>Si vous n'êtes pas à l'origine de cette opération, attention, car quelqu'un semble roder autour de votre compte... :-(</p><p>PS : Ce lien expirera après 10 minutes, alors ne tardez pas trop...</p>";
    mail($to, $subject, $msg, $headers);
    header('location: pwdrecovery/pending.php?email=' . $email);
  }
}