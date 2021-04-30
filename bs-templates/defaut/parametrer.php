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
	header('Location: '.$url_annuaire.'parametrer.html');
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
		<a href="<?php echo $url_annuaire; ?>">Annuaire</a> &gt; Paramétrer mes sites
	</p>	
	</div>
	
		
				<?php $myaccount = $_SESSION['mail_user'];$mywebsites = rcp_pro_websites($myaccount, "date"); if (!empty($mywebsites)) { ?>
					<div class="space" id="mes_sites_saved">
						
							<div class="titre_colonne">📊 Editer / Booster mes sites enregistrés</div>
							
							<div class="textostat">
								<ol>
									<?php
									foreach($mywebsites as $m_s){
										$monjolititre 	= $m_s['titre'];
										$majolieid 		= $m_s['id_site'];
										$hk_plink 		= $m_s['info']['permanlink'];
										$proquidam 		= $m_s['info']['quidam'];
										$date2val		= new DateTime($m_s['date2validation']);
										$date2val 		= $date2val->format('d/m/Y');
										$proboost_t		= '<form action="boostmywebsite.html" method="POST" onsubmit="return confirm(\'Je veux booster ce site dans les premières places de sa catégorie + 1 backlink depuis la section dans laquelle il est répertorié contre 1 crédit ?\');" class="booster_mon_site"><button type="submit" name="id_site" value="' .$majolieid. '">🚀 Booster</button></form>';
										$pro_edit 		= '<form action="updatemywebsite.html" method="POST"class="editer_mon_site"><button type="submit" name="id_site" value="' .$majolieid. '">⚙️ Editer</button></form>';
										if ($proquidam <= 10) {$pro_label = "🥉";$pro_boost=$proboost_t;} elseif ($proquidam <= 20) {$pro_label = "🥈";$pro_boost=$proboost_t;} elseif ($proquidam <= 70) {$pro_label = "🥇";$pro_boost=NULL;} else {$pro_label = "🏆";$pro_boost=NULL;}
										$filetofish 	= $m_s['type'];
										if ($filetofish == 1) {$hk_plink = $m_s['info']['permanlink'];$prendsunbigmac = "";} else {$hk_plink = "#"; $prendsunbigmac = "oui";}
									?>	
									
										<li>
											<span>
												<?php echo "📅 $date2val | $pro_label"; ?> 
												<a href="<?php echo $hk_plink; ?>" <?php if (($prendsunbigmac == 'oui') AND ($amp != true)) {?>onclick="pasdeFish();return false;" style="color:#069924;cursor:not-allowed;"<?php } ?>>
													<?php echo $monjolititre; ?><?php if ($prendsunbigmac == 'oui') {echo " - #$majolieid";} ?>
												</a>
											</span>
											<?php 
											
												echo $pro_edit;
												if (isset($pro_boost)) {
													echo $pro_boost;
												}

											?>
										</li>
									
									<?php } ?>
								</ol>
							</div>
						</div>
						
						<?php if ($coins <= 0) {?>
					
					<div class="info_jaune"><p>⚠️ ATTENTION, vous avez actuellement <span style='color:red;font-size:150%;'>0 crédits</span> et vous ne pouvez donc pas bénéficier des avantages réservés aux comptes PREMIUM tant que vous n'avez pas chargé des unités sur votre compte.</p><p>Merci <?php if ($module_de_paiement == TRUE) {?>de recharger ci-dessous votre compte ou <?php } ?>de <a href='contact.html'>nous contacter</a> au plus vite pour activer/renouveler vos avantages partenaires PREMIUM.</div>
					
				<?php } ?>
				
					<?php if ($module_de_paiement == TRUE) { include 'payp.php'; } ?>
		
	<?php } else { ?>
	
	<div class="info_jaune"><p>Aucun site n'est rattaché à ce compte. Si vous pensez qu'il s'agit d'une erreur, merci de <a href='contact.html'>nous contacter</a> au plus vite pour activer/renouveler vos avantages partenaires PREMIUM.</p></div>
	
	<?php } ?>

</div>



<?php

include 'footer.php'; 

?>