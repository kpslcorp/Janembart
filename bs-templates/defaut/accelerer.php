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
	header('Location: '.$url_annuaire.'accelerer.html');
	}
include 'header.php';

$id = valid_donnees($_GET["id"]); 
?>
		 
<div id="page_content">
	<h1>✅ Accélérer la validation</h1>
	
	<div id="breadcrumb">
	<p id="fil_ariane">
		<a href="<?php echo $url_annuaire; ?>">Annuaire</a> &gt; Accélérer
	</p>	
	</div>
	
	<?php if ($module_de_paiement == TRUE) { 
	
		echo "<div class='info_jaune'><p>Pour valider en moins de 48h votre site, vous pouvez soit <u><a href='contact.html'>nous contacter</a></u> pour définir un moyen de paiement, soit payer en ligne via le formulaire ci-dessous.</p></div><h2>Formulaire de paiement</h2>";
		
		include 'payp_fl.php'; 
		
	} else {
		
		echo "<div class='info_jaune'><p>Pour accélérer la validation de votre site sur notre annuaire, nous vous invitons à <u><a href='contact.html'>nous contacter</a></u> afin de définir ensemble le nombre de crédits que vous souhaitez ajouter ainsi que le moyen de paiement que vous souhaitez utiliser.</p></div>";
		
	}?>

</div>


<?php

include 'footer.php'; 

?>