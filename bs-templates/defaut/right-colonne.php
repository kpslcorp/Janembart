<?php 
// Module pour empecher le include d'être appelé directement
if (empty($variable_temoin))
{
exit("Quelque-chose me dit que vous n'avez rien à faire ici ?!");
} 
// Module pour empecher le include d'être appelé directement
?>

</div>
			
		<div id="colonne">
			
			<div class="space">
				<?php if (isset($_SESSION['loggedin'])){ $titre_block_webmaster = "⬇️ Espace Pro"; } else { $titre_block_webmaster = "⬇️ Webmasters";} // Si compte pro ?>
				<div class="titre_colonne"><?php echo $titre_block_webmaster; ?></div>
				<div class="widget_colonne">
					<ul>
						<li><a href="ajouter.html">🆕 Ajouter un site</a></li>	
						<?php if ((isset($_SESSION['loggedin'])) AND ($_SESSION['statut'] == "pr0")){ // Si Compte PRO (Client)	?>
							<?php if ($coins > 0) {$colorcoin = "green";} else {$colorcoin = "red";} ?>
							<li>💰 <span style="color:<?php echo $colorcoin; ?>;"><?php echo $coins; ?></span> crédit(s) restante(s)</li>
							<li>⚙️ <a href="parametrer.html">Paramétrer mes sites</a></li>
							<li>💳 <a href="recharger.html">Acheter des crédits</a></li>
							<li>📧 <a href="contact.html">Contact</a></li>
							<li>❌ <a href="acces-pro/logout.php">Déconnexion</a></li>
						<?php } ?>	
						<?php if ((isset($_SESSION['loggedin'])) AND ($_SESSION['statut'] == "kaioh")){ // Si ADMIN ?>
							<li>🏚️ <a href="gestion">Zone Admin</a></li>
							<li>🚮️ <a href="gestion/?act=cache">Vider le Cache</a></li>
							<li>❌ <a href="gestion/logout.php">Déconnexion</a></li>
						<?php } ?>
					</ul>
				</div>
			</div>
			
			
				<?php if (!(isset($_SESSION['loggedin'])) OR ($_SESSION['statut'] == "kaioh")){ // Si ADMIN ou non connecté ?>
			
				<div class="space">
					
					<div class="titre_colonne">🌶️ Nouveautés</div>
						
					<div class="widget_colonne">
						
						<ul>
							
							<?php 
								
								$site = rcp_site("", "", "ORDER BY date2validation DESC", 1, 1, "LIMIT 0, 5");
								
								foreach($site as $s){
				
							?>	
									<li><a href="<?php echo $s['info']['permanlink']; ?>" title="<?php echo stripslashes($s['titre']); ?>"><?php echo stripslashes($s['titre']); ?></a></li>
								
							<?php } ?>
								
						</ul>
							
					</div>
						
				</div>
				
				<?php } ?>
					
				<?php if (!isset($_SESSION['loggedin'])){ ?>

				<div class="space">
					
					<div class="titre_colonne">🏆 Top 5 Sites</div>
						
						<div class="widget_colonne">
						
							<ul>
							
								<?php 
								
								$site = rcp_site("", "", "ORDER BY note DESC", 1, 1, "LIMIT 0, 5");
								
								foreach($site as $s){ 
								
								?>	
								
								<li><a href="<?php echo $s['info']['permanlink']; ?>" title="<?php echo stripslashes($s['titre']); ?>"><?php echo stripslashes($s['titre']); ?></a></li>
								
								<?php } ?>
								
							</ul>
							
						</div>
						
				</div>
				

				<div class="space">
				
					<div class="titre_colonne">🗨️ Catégories</div>
					
					<div class="widget_colonne">
						
						<ul>
						
						<?php

							$cat = rcp_cat(0, 'ORDER by titre ASC');
							
							foreach($cat as $c){
			
						?>	

						<li><a href="<?php echo $url_annuaire.'3.'.$c['id_cat'].'-'.clean($c['titre']).$extension; ?>"><?php echo stripslashes($c['titre']); ?></a></li>
					
						<?php } ?>
						</ul>
					

					</div>
				</div>
				
				<div class="space">
				
					<div class="titre_colonne">📊 Stats</div>
					
					<div class="textostat">
						<ul>
							<li><?php echo stat_site (1, ''); ?> sites répertoriés</li>
							<li><?php echo stat_site (2, ''); ?> sites en attente</li>
							<li><?php echo stat_section(''); ?> sections</li>
							<li><?php echo stat_categorie(); ?> rubriques</li>
						</ul>
					</div>
					
				</div>
				
				<div class="space">
				
					<div class="titre_colonne">ℹ️ À propos</div>
					
					<div class="textostat">
					<?php 
					echo "<ul>"; 
					echo "<li><a href='https://janembart.com/'>Site Officiel</a></li>";
					echo "<li><a href='https://janembart.com/faq/'>FAQ</a></li>";
					echo "<li><a href='contact.html'>Contact</a></li>";
					
					$get_order = 'id_page DESC';
					$page = rcp_page('', 'ORDER BY '.$get_order);
					 
					 foreach ($page as $p) { 
						$page_index.='<li><a href="4.'.$p['id_page'].'-'.clean($p['titre']).$extension.'">'.stripslashes($p['titre']).'</a></li>';
					} 
					echo $page_index;
				
					echo"</ul>"; 
					?>
					</div>
					
				</div>
				
			<?php } else { // Si Connecté?>
			
				<?php if ($ajouter_traitement_hilde != "oui") { ?>
				
					<?php 	$myaccount = $_SESSION['mail_user'];
							$mywebsites = rcp_pro_websites($myaccount);
							if (!empty($mywebsites)) { ?>
			
					<div class="space" id="mes_sites_saved">
					
						<div class="titre_colonne">📊 Mes Sites Enregistrés</div>
						
						<div class="textostat">
							<ol>
								<?php
								foreach($mywebsites as $m_s){
									$monjolititre 	= $m_s['titre'];
									$majolieid 		= $m_s['id_site'];
									$hk_plink 		= $m_s['info']['permanlink'];
									$proquidam 		= $m_s['info']['quidam'];
									$proboost_t		= '<form action="boostmywebsite.html" method="POST" onsubmit="return confirm(\'Je veux booster ce site dans les premières places de sa catégorie + 1 backlink depuis la section dans laquelle il est répertorié contre 1 crédit ?\');" class="booster_mon_site"><button type="submit" name="id_site" value="' .$majolieid. '">🚀 Booster</button></form>';
									$pro_edit 		= '<form action="updatemywebsite.html" method="POST"class="editer_mon_site"><button type="submit" name="id_site" value="' .$majolieid. '">⚙️ Editer</button></form>';
									if ($proquidam <= 10) {$pro_label = "🥉";$pro_boost=$proboost_t;} elseif ($proquidam <= 20) {$pro_label = "🥈";$pro_boost=$proboost_t;} elseif ($proquidam <= 70) {$pro_label = "🥇";$pro_boost=NULL;} else {$pro_label = "🏆";$pro_boost=NULL;}
									$filetofish 	= $m_s['type'];
									if ($filetofish == 1) {$hk_plink = $m_s['info']['permanlink'];$prendsunbigmac = "";} else {$hk_plink = "#"; $prendsunbigmac = "oui";}
								?>	
									<li>
										<span style="display:block;">
											<?php echo $pro_label; ?> 
											<?php if ($_SESSION['statut'] == "kaioh") {echo "<a href='gestion/?act=1&id=$majolieid'>⚙️</a>";} ?>
											<a href="<?php echo $hk_plink; ?>" <?php if (($prendsunbigmac == 'oui') AND ($amp != true)) {?>onclick="pasdeFish();return false;" style="color:#069924;cursor:not-allowed;"<?php } ?>>
												<?php echo $monjolititre; ?><?php if ($prendsunbigmac == 'oui') {echo " - #$majolieid";} ?>
											</a>
										</span>
										<?php 
										if ($_SESSION['statut'] == "pr0") {
											echo $pro_edit;
											if (isset($pro_boost)) {
												echo $pro_boost;
											}
										}
										?>
									</li>
								
								<?php } ?>
							</ol>
						</div>
						<?php if ($amp != true) { ?>
						<script>
							function pasdeFish() { alert("Ce site ne possède pas de fiche dédiée. Le lien se fait directement depuis la section dans laquelle il est répertorié !");}
						</script>
						<?php } ?>
						
					</div>
				
					<?php } ?>
				
				<?php } ?>
			
			<?php } ?>
					
			</div>
			
		</div>