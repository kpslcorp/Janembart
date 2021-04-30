<?php
session_start();

// Module pour empecher le include d'être appelé directement
if (empty($variable_temoin))
{
exit("Quelque-chose me dit que vous n'avez rien à faire ici ?!");
} 
// Module pour empecher le include d'être appelé directement

$amp = $_GET["amp"]; 
if(isset($amp)) { 
	header("Status: 301 Moved Permanently", false, 301);
	header('Location: '.$url_annuaire.'recharger.html');
}


// Page réservée aux comptes premiums

		if ((!isset($_SESSION['loggedin'])) OR ($_SESSION['statut'] != "pr0")){ 
	
		header("Status: 301 Moved Permanently", false, 301);
		header('Location: '.$url_annuaire.'');
		
		}
// FIN DU MODULE

include 'header.php';
?>
		 
<div id="page_content">
	<h1>✅ Paramétrer mes sites</h1>
	
	<div id="breadcrumb">
	<p id="fil_ariane">
		<a href="<?php echo $url_annuaire; ?>">Annuaire</a> &gt; Recharger du crédit
	</p>	
	</div>
	
	<?php if ($module_de_paiement == TRUE) { 
	
		echo "<div class='info_jaune'><p>Pour recharger des crédits sur votre compte, vous pouvez soit <u><a href='contact.html'>nous contacter</a></u> afin de définir ensemble le nombre de crédits que vous voulez ajouter et le moyen de paiement, soit payer directement en ligne ci-dessous.</p></div>";
		
		include 'payp.php'; 
		
	} else {
		
		echo "<div class='info_jaune'><p>Pour recharger des crédits sur votre compte, nous vous invitons à <u><a href='contact.html'>nous contacter</a></u> afin de définir ensemble le nombre de crédits que vous voulez ajouter ainsi que le moyen de paiement.</p></div>";
		
	}?>

</div>


<?php

include 'footer.php'; 

?>