<?php 
$title = "Mail de réinitialisation envoyé !";
include ('../../gestion/head.php'); ?>


	<form id="janembart-form" class="janembart-form">
		<p>
			📧 Nous venons de vous envoyer un email sur l'adresse <b><?php echo $_GET['email'] ?></b> afin de vous aider à avoir à nouveau accès à votre espace d'administration.
		</p>
		<p>👉 Rendez-vous sur votre boite mail et suivez les instructions que nous vous avons communiqué.</p>
		<p>🤓 Vous n'avez rien reçu ? Pensez à regarder vos spams</p>
	</form>
		
<?php include ('../../gestion/foot.php'); ?>