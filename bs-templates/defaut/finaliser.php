<?php
session_start();

// Sécurité anti-include direct
if (empty($variable_temoin)) {
    exit("Quelque-chose me dit que vous n'avez rien à faire ici ?!");
}
// Module pour empecher le include d'être appelé directement

$amp = $_GET["amp"]; 
if (isset($amp)) { 
    header("Status: 301 Moved Permanently", false, 301);
    header('Location: '.$url_annuaire.'finaliser.html');
    exit();
}

include 'header.php';

// Si id manquant dans l'URL => R301 directe
if (empty($_GET["id"])) {
    header("Status: 301 Moved Permanently", false, 301);
    header("Location: $url_annuaire");
    exit();
}

$id = (int) valid_donnees($_GET["id"]); 
$site_exist = rcp_site($id, "", "", "", "", "");
$s = $site_exist[0];

// Préparation du message d'erreur si besoin
$message_erreur = '';

if (empty($s) || !is_array($s)) {
    $message_erreur = "<p style='color:red;text-align:center;'>❌ Aucun site affecté à cet ID n'a été retrouvé dans notre annuaire. Merci de <a href='/contact.html'>nous contacter</a> pour y voir plus clair.</p>";
} else {
    $valide = (int)$s['valide'];
    if ($valide == 4) {
        $message_erreur = "<p style='color:red;text-align:center;'>❌ Un paiement semble déjà avoir été effectué pour ce site. Le process devrait suivre son cours mais passé 48h ouvrés, merci de <a href='/contact.html'>nous contacter</a> pour vérifier où ça en est.</p>";
    } elseif ($valide == 1) {
        $message_erreur = "<p style='color:red;text-align:center;'>❌ Ce site est déjà en ligne dans notre annuaire, il n'y a plus rien à payer.</p>";
    }
}

?>
		 
<div id="page_content">
	<h1>✅ Finalisez votre inscription</h1>
	
	<div id="breadcrumb">
        <p id="fil_ariane">
            <a href="<?php echo $url_annuaire; ?>">Annuaire</a> &gt; Finaliser
        </p>    
    </div>
	
	<?php
    // Si un message d'erreur a été défini, on l'affiche
	
    if (!empty($message_erreur)) {
		
        echo $message_erreur;
		
    } else {
		
		if ($module_de_paiement == TRUE) { 
	
			echo "<div class='info_jaune'>
					<p><strong>Le paiement est obligatoire pour finaliser l'inscription de votre site.</strong></p>
					<p>Vous pouvez régler directement en ligne via le formulaire ci-dessous, ou <u><a href='contact.html'>nous contacter</a></u> si vous préférez un autre moyen de paiement.</p>
				</div>";
				
			require_once ('recap_pricing.php'); 
			require_once ('payp_fl.php'); 
			
			echo "<p style='color:red; text-align:center;'>⚠️ Merci de noter que seuls les sites 100% légaux pourront être validés. Nous refuserons systématiquement, sans remboursement possible, les sites liés au warez, streaming illégal, casino, maraboutage, contenus pour adultes, viagra, forex, etc.</p>";
			
		} else {
			
			echo "<div class='info_jaune'>
					<p>Pour accélérer la validation de votre site sur notre annuaire, nous vous invitons à <u><a href='contact.html'>nous contacter</a></u> afin de définir ensemble le nombre de crédits que vous souhaitez ajouter ainsi que le moyen de paiement que vous souhaitez utiliser.</p><p style='color:red;text-align:center;'>Attention, seuls les sites 100% légaux pourront être validés ET nous refuserons systématiquement et sans remboursement possible les sites de warez, streaming illégal, casino, marabout, porno, viagra, forex etc...</p>
			</div>";
			
		}
		
	}
	
	?>
	
	
</div>


<?php

include 'footer.php'; 

?>