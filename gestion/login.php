<?php 
require(dirname(__FILE__).'/../config.php');
$title = "Login";
include ('head.php'); ?>

					<form method="post" id="janembart-form" class="janembart-form" action="auth.php">
					<h2>Admin Namek</h2>
					<div class="form-group">
						<input type="email" name="email" required="required" class="form__input" placeholder="email">
					</div>
					<div class="form-group">
						<input type="password" name="password" required="required" class="form__input" placeholder="password">
					</div>
					<div class="form-group">
						 <input type="submit" name="submit" id="submit" class="form__submit" value="Se connecter">
					 </div>   
					</form>
					<p style="text-align:center;"><a href="<?php echo $url_annuaire."config/reset.php"; ?>">Mot de passe oublié ?</a></p>
					
<?php include ('foot.php'); ?>