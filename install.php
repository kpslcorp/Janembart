<?php 
$title="Installation de Janembart";
include ('gestion/head.php');

$way_of_the_fight = getcwd();
$xfiles = "$way_of_the_fight/config.php"; // Construction du nom du fichier

if (file_exists($xfiles)) {
	exit("L'annuaire semble déjà installé. Merci de supprimer le fichier config.php du serveur, de supprimer les tables SQL de votre base SQL, et de suivre les instructions du readme.txt pour re-lancer une nouvelle installation.");
}
?>
		

					<form method="post" id="kasuple-form" class="kasuple-form" action="setup_process.php">
					<h2>⚙ Installation de l'annuaire</h2>
					
					<?php 
					if (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) ||  isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&  $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {}
					else {
							echo "<div style='background: red;color: white;font-weight: bold;text-align: center;padding: 20px;font-size: 20px;border-radius: 8px;'>⚠️ Attention, pour que l'installation se déroule correctement, ce script doit être appelé via une page HTTPS</div>";
					}
					?>
					
					<h3>👊 Admin</h3>
					<div class="form-group">
						<input type="email" name="email" required="required" class="form__input" placeholder="Votre adresse email" autocomplete>
					</div>
					<div class="form-group">
						<input id="psw" type="password" name="password" required="required" class="form__input" placeholder="Votre password" required pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Minimum 8 caractères avec au moins : 1 majuscule, 1 minuscule et 1 chiffre.">
						<div id="help4password">
							<ul>
								<li id="letter" class="invalid">1 lettre minuscule</li>
								<li id="capital" class="invalid">1 lettre majuscule</li>
								<li id="number" class="invalid">1 nombre</li>
								<li id="length" class="invalid">8 caractères</li>
							</ul>
						</div>
					</div>
					
					<h3>🦚 Annuaire</h3>
					<div class="form-group">
						<input type="text" name="titre_annuaire" required="required" class="form__input" placeholder="Titre Annuaire">
					</div>
					<div class="form-group">
						<input type="text" name="description_annuaire" required="required" class="form__input" placeholder="Headline : Punchline décrivant l'annuaire !">
					</div>
		

					
					<h3>⚡ Connexion à la BDD</h3>
					<div class="form-group">
						<input type="text" name="serveur" required="required" class="form__input" placeholder="Serveur (ex : Localhost)">
					</div>
					<div class="form-group">
						<input type="text" name="nom_utilisateur" required="required" class="form__input" placeholder="Nom Utilisateur SQL">
					</div>
					<div class="form-group">
						<input type="text" name="pass" required="required" class="form__input" placeholder="Password MySQL">
					</div>
					<div class="form-group">
						<input type="text" name="base_de_donnee" required="required" class="form__input" placeholder="Nom de la base de donnée">
					</div>
					<div class="form-group">
						<input type="text" name="prefixe_sql" required="required" class="form__input" placeholder="Prefixe des tables">
					</div>
					
					<h3>⚙️ Configuration</h3>
					<div class="form-group">
						<p>Combien de caractères (min) devra faire la description d'un site proposé ?</p>
						<input type="number" name="nb_cara_description" class="form__input" placeholder="Charactères Min exigés pour la description" required="required" value="600"> 
					</div>
					<div class="form-group">
						<p>Exigez vous un lien retour ?</p>
						<select name="forcebacklink" class="form__input">
							<option value="non">Nop</option>
							<option value="oui">Oui</option>
						</select>
					</div>
					<div class="form-group">
						<p>Souhaitez-vous recevoir un mail lorsqu'un nouveau site est soumis ?</p>
						<select name="msg2modo" class="form__input">
							<option value="all">OUI - Toujours</option>
							<option value="spoted">Oui, s'il a mis un lien retour</option>
							<option value="">Jamais</option>
						</select>
					</div>
					<div class="form-group">
						<p>Comment sont triés les sites dans les catégories ?</p>
						<select name="sort4site" class="form__input">
							<option value="new2old">Par date de soumission (Nouveau > Ancien)</option>
							<option value="old2new">Par date de soumission (Ancien > Nouveau)</option>
							<option value="note">Par la note attribuée par le modérateur</option>
							<option value="az">Par ordre alphabétique A-Z</option>
						</select>
					</div>
					
					<div class="form-group">
						<p>Combien de fiches sites souhaitez-vous afficher dans chaques pages catégories (5 - 25)</p>
						<input type="number" name="nb_fiche_section" class="form__input" placeholder="Nombre sites par pages catégorie" required="required" value="10"> 
					</div>
					
					<h3>🌐 SEO</h3>
					<div class="form-group">
						<p>Balise title de votre Homepage</p>
						<input type="text" name="hp_metatitle" class="form__input" placeholder="Sera utilisé pour remplir la balise title">
					</div>
					
					<div class="form-group">
						<p>Meta description de votre Homepage</p>
						<input type="text" name="hp_metadesc" class="form__input" placeholder="Sera utilisé pour remplir la balise meta description">
					</div>
		
					<div class="form-group">
						<p>ID Google Analytics : Laissez vide si vous n'utilisez pas GA</p>
						<input type="text" name="google_analytics" class="form__input" placeholder="UA-XXXX"> 
					</div>
					
					<div class="form-group">
						 <input type="submit" name="submit" id="submit" class="form__submit" value="🚀 Installer">
					 </div>    
					</form>
					
				
<script>var myInput=document.getElementById("psw"),letter=document.getElementById("letter"),capital=document.getElementById("capital"),number=document.getElementById("number"),length=document.getElementById("length");myInput.onfocus=function(){document.getElementById("help4password").style.display="block"},myInput.onblur=function(){document.getElementById("help4password").style.display="none"},myInput.onkeyup=function(){myInput.value.match(/[a-z]/g)?(letter.classList.remove("invalid"),letter.classList.add("valid")):(letter.classList.remove("valid"),letter.classList.add("invalid"));myInput.value.match(/[A-Z]/g)?(capital.classList.remove("invalid"),capital.classList.add("valid")):(capital.classList.remove("valid"),capital.classList.add("invalid"));myInput.value.match(/[0-9]/g)?(number.classList.remove("invalid"),number.classList.add("valid")):(number.classList.remove("valid"),number.classList.add("invalid")),myInput.value.length>=8?(length.classList.remove("invalid"),length.classList.add("valid")):(length.classList.remove("valid"),length.classList.add("invalid"))};</script>

<?php include ('gestion/foot.php'); ?>