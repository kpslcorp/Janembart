<?php
session_start();
$smarttoken = bin2hex(random_bytes(50)); // Génération d'un token
$arr_cookie_options_reset = array (
                'expires' => time()+300,  // 5 minutes
                //'path' => '/',
                //'domain' => '.example.com', // leading dot for compatibility or use subdomain
                //'secure' => false,     // or false
                'httponly' => true,    // or false
				// 'samesite' => 'Lax' // None || Lax  || Strict
                );
				
setcookie("securz",$smarttoken,$arr_cookie_options_reset) ; // Génération du Cookie
$_SESSION['smarttoken'] = $smarttoken; // Génération de la variable de session

// connect to database
require(dirname(__FILE__).'/../../config.php');
$db = mysqli_connect($serveur, $nom_utilisateur, $pass, $base_de_donnee);

$i_reset = SQL_PREFIXE.'reset';
$i_user = SQL_PREFIXE.'u';

$gettoken = NULL;
$gettoken = mysqli_real_escape_string($db, $_GET['tiktok']);
$gettoken = hash_hmac("sha256", $gettoken, $pepper);

$sql = "SELECT * FROM $i_reset WHERE token='$gettoken' LIMIT 1";

$results = mysqli_query($db, $sql);
$monbeautableau = mysqli_fetch_assoc($results);
$email = $monbeautableau['email']; 

/* Expiration du token après 30 minutes */
$salledutemps = $monbeautableau['salledutemps'];
$salledutemps = strtotime($salledutemps);
$salledutemps_expire = $salledutemps + 600;
$salledutemps_now = strtotime(date('Y-m-d H:i:s'));

if (!empty($email)) { 

	if ($salledutemps_now > $salledutemps_expire) {
		die ("Ce lien n'est plus actif, merci de relancer la procédure de régénération du mot de passe");
	}  
		
	else { 
	
	$id_du_token = $monbeautableau['id']; 
	$_SESSION['tokenexpire'] = "nop"; // Le token est valide
	$_SESSION['id_du_token'] = $id_du_token; // ID de la procédure (pour supprimer en step 2)
	
	$title = "Réinitialisation du mot de passe";
	include ('../../gestion/head.php'); ?>

	<form action="next_passwd2.php" method="post" id="janembart-form" class="janembart-form">
		<h2 class="form-title">Créer un nouveau mot de passe</h2>
		<!-- form validation messages -->
		<?php include('messages.php'); ?>
		<div class="form-group">
			<label>Nouveau mot de passe</label>
			<input id="psw" type="password" name="new_pass" required pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Minimum 8 caractères avec au moins : 1 majuscule, 1 minuscule et 1 chiffre." required="required" class="form__input">
			<div id="help4password">
							<ul>
								<li id="letter" class="invalid">1 lettre minuscule</li>
								<li id="capital" class="invalid">1 lettre majuscule</li>
								<li id="number" class="invalid">1 nombre</li>
								<li id="length" class="invalid">8 caractères</li>
							</ul>
			</div>
		</div>
		<div class="form-group">
			<label>Confirmer mon nouveau mot de passe</label>
			<input type="password" name="new_pass_c" required="required" class="form__input">
		</div>
		<div style="display:none;">
			<input type="mail" name="el_mail" required="required" value="<?php echo $email; ?>" >
		</div>
		<div class="form-group">
			<input id="submit" type="submit" name="reset-password" class="form__submit" value="Envoyer">
		</div>
	</form>
	
	<script>var myInput=document.getElementById("psw"),letter=document.getElementById("letter"),capital=document.getElementById("capital"),number=document.getElementById("number"),length=document.getElementById("length");myInput.onfocus=function(){document.getElementById("help4password").style.display="block"},myInput.onblur=function(){document.getElementById("help4password").style.display="none"},myInput.onkeyup=function(){myInput.value.match(/[a-z]/g)?(letter.classList.remove("invalid"),letter.classList.add("valid")):(letter.classList.remove("valid"),letter.classList.add("invalid"));myInput.value.match(/[A-Z]/g)?(capital.classList.remove("invalid"),capital.classList.add("valid")):(capital.classList.remove("valid"),capital.classList.add("invalid"));myInput.value.match(/[0-9]/g)?(number.classList.remove("invalid"),number.classList.add("valid")):(number.classList.remove("valid"),number.classList.add("invalid")),myInput.value.length>=8?(length.classList.remove("invalid"),length.classList.add("valid")):(length.classList.remove("valid"),length.classList.add("invalid"))};</script>
	
<?php include ('../../gestion/foot.php'); ?>

<?php } } else {die ("Erreur. Merci de relancer la procédure");} ?>