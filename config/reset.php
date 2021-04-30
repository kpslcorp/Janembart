<?php require('pwdrecovery/app_logic.php'); ?>

<?php 
require(dirname(__FILE__).'/../config.php');
$title = "Password Reset PHP";
include ('../gestion/head.php'); ?>

	<form id="janembart-form" class="janembart-form" action="reset.php" method="post">
		<h2 class="form-title">Reset password</h2>
		<!-- form validation messages -->
		<?php include('pwdrecovery/messages.php'); ?>
		<div class="form-group">
			<label>Votre adresse email :</label>
			<input type="email" name="email" class="form__input" required="required">
		</div>
		<div class="form-group">
			<input id="submit" type="submit" name="reset-password" class="form__submit" value="Soumettre">
		</div>
	</form>

<?php include ('../gestion/foot.php'); ?>