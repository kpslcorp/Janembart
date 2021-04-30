<?php 
session_start();
// connect to database
require(dirname(__FILE__).'/../../config.php');
$db = mysqli_connect($serveur, $nom_utilisateur, $pass, $base_de_donnee);

$i_reset = SQL_PREFIXE.'reset';
$i_user = SQL_PREFIXE.'u';

$new_pass = mysqli_real_escape_string($db, $_POST['new_pass']);
$new_pass_c = mysqli_real_escape_string($db, $_POST['new_pass_c']);
$email = mysqli_real_escape_string($db, $_POST['el_mail']);

?>

<?php 
$title = "Réinitialisation du mot de passe";
include ('../../gestion/head.php'); ?>

<form class="login-form">
		<h2 class="form-title">Bieeeen ! Alors ?</h2>
		<div class="form-group">

<?php
$error = 0;
$linkback = "<p style='margin-top:60px;text-align:center;'><a href='javascript:history.back()' class='form__submit' style='text-align:center;display:block;'>< Retour</a></p>";
if (!isset($_POST['reset-password'])){echo "<p style='text-align:center;'>Erreur : Comment es-tu arrivé là sans remplir le formulaire ?</p>";$error = 1;session_destroy();exit();}
if (empty($new_pass) || empty($new_pass_c)) {echo "<p style='text-align:center;'>Erreur : Password incorrect</p>$linkback";$error = 1;session_destroy();unset ($_COOKIE['securz']);exit();} 
if ($new_pass !== $new_pass_c)  {echo "<p style='text-align:center;'>Erreur : Les passwords ne sont pas identiques</p>$linkback";$error = 1;session_destroy();unset ($_COOKIE['securz']);exit();} 
if (filter_var($email, FILTER_VALIDATE_EMAIL) == false) {echo "<p style='text-align:center;'>Erreur : Boite mail invalide</p>$linkback";$error = 1;session_destroy();unset ($_COOKIE['securz']);exit();} 
if (($_COOKIE["securz"]) !== ($_SESSION["smarttoken"])) {echo "<p style='text-align:center;'>Erreur : Vide tes cookies et redémarre ton navigateur pour réinitialiser la manip'</p>";$error = 1;session_destroy();unset ($_COOKIE['securz']);exit();} 
if ($_SESSION['tokenexpire'] != "nop") {echo "<p style='text-align:center;'>Erreur : Votre Token a expiré ! Merci de relancer la procédure</p>$linkback";$error = 1;session_destroy();unset ($_COOKIE['tokenexpire']);exit();} 

if ($error == 0) {

		$pwd_peppered = hash_hmac("sha256", $new_pass, $pepper);
		$new_pass = password_hash($pwd_peppered, PASSWORD_DEFAULT);
		$sql = "UPDATE $i_user SET password='$new_pass' WHERE mail='$email';DELETE FROM $i_reset WHERE id ='".intval($_SESSION['id_du_token'])."'";
		$results = mysqli_multi_query($db, $sql);
		
		$login_url = $url_annuaire.'acces-pro/login.php';
		echo "<p style='text-align:center;'>Votre mot de passe a bien été modifié</p><p style='margin-top:60px;text-align:center;'><a href='$login_url' class='form__submit' style='text-align:center;display:block;'>Connexion à l'admin</a></p>";

  }
    
?>
</div>
</form>

<?php include ('../../gestion/foot.php'); 
session_destroy();
unset ($_COOKIE['securz']);
?>