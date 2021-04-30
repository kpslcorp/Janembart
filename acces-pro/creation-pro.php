<?php
session_start();
$title="Création Pro";
include ('../bs-includes/kaptchaa.php');
include ('../gestion/head.php'); ?>
		

					<form method="post" id="kasuple-form" class="kasuple-form" action="creationprocess.php">
					<h2>🔥 Création Compte Pro</h2>
					
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
					
					<h3>🤖 Répondez à la question de sécurité :</h3>
		
					<p>❓ <?php echo $question_q; ?> ❓</p>
		
					<div class="form-group">
						<input id="security_code" name="security_code" type="text" class="form__input" required="required" autocomplete="off" />
					</div>
					
					<h3>Politique de Confidentialité</h3>
		
					<div class="form-group">
						<p style="font-size:12px;"><input type="checkbox" id="rpgd" name="rpgd" value="Consentement OK" required="required" /> <input type="checkbox" id="cgv-u" name="cgv-u" /> <label for="rpgd">Ce site respecte le RGPD. En soumettant ce formulaire, j'accepte que les informations fournies soient stockés par l'éditeur du site afin de pouvoir me donner un accès pro. Il vous suffit de nous contacter pour cloturer votre compte ou pour modifier vos données personnelles.</label></p>
					</div>
					
					<h3>Conditions générales d'utilisation</h3>
		
					<div class="form-group">
						<p><input type="checkbox" id="cgv" name="cgv" value="CGV OK" required="required" /> <label for="cgv">En cochant cette case je m'engage à ne <strong>JAMAIS publier de contenu dupliqué, illégal ou immoral</strong>, ou d'<strong>effectuer de liens vers du contenu illégal</strong>. <span style="background:yellow;">En cas de violation de cette condition, mes publications seront supprimées, mon compte sera fermé et mes crédits définitivement perdus sans aucun remboursement possible.</span></label></p>
					</div>
					
					
					<div class="form-group">
						 <input type="submit" name="submit" id="submit" class="form__submit" value="🚀 Let's Go !">
					 </div>    
					</form>
					
				
<script>var myInput=document.getElementById("psw"),letter=document.getElementById("letter"),capital=document.getElementById("capital"),number=document.getElementById("number"),length=document.getElementById("length");myInput.onfocus=function(){document.getElementById("help4password").style.display="block"},myInput.onblur=function(){document.getElementById("help4password").style.display="none"},myInput.onkeyup=function(){myInput.value.match(/[a-z]/g)?(letter.classList.remove("invalid"),letter.classList.add("valid")):(letter.classList.remove("valid"),letter.classList.add("invalid"));myInput.value.match(/[A-Z]/g)?(capital.classList.remove("invalid"),capital.classList.add("valid")):(capital.classList.remove("valid"),capital.classList.add("invalid"));myInput.value.match(/[0-9]/g)?(number.classList.remove("invalid"),number.classList.add("valid")):(number.classList.remove("valid"),number.classList.add("invalid")),myInput.value.length>=8?(length.classList.remove("invalid"),length.classList.add("valid")):(length.classList.remove("valid"),length.classList.add("invalid"))};</script>

<?php include ('../gestion/foot.php'); ?>