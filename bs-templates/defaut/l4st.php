<?php

$nb_sites_affiches = 30;

// Module pour empecher le include d'etre appelé directement
if (empty($variable_temoin)){
	
	exit("Quelque-chose me dit que vous n'avez rien à faire ici ?!");
	
} 
// ! Module pour empecher le include d'etre appelé directement

include 'header.php'; 

?>

<div id="page_content">
			
	<div id="breadcrumb">
		<p id="fil_ariane">
			<a href="<?php echo $url_annuaire; ?>">Annuaire</a> &gt; Nouveautés
		</p>	
	</div>
		
	<div class="align_center">
		<h1 class="color_titre"><?php echo $nb_sites_affiches; ?> Derniers Sites Inscrits</h1>
	</div>	
	
	<?php 
	
	$site = rcp_site("", "", "ORDER BY date2validation DESC", 1, 1, "LIMIT 0, $nb_sites_affiches");				
				
	if(!empty($site)) { // Si on a au moins un site, on déroule... ?>	
	
		<div>
	
		<ul class="liste_site">
	
			<?php
				
			if(is_array($site)) {
				
				foreach($site as $s){
		
					if($modulo % 2 == 0) { echo '<li>'; } else { echo '<li class="site_modulo">'; } $modulo++; ?>
			
						<?php // MEA des Sites Pros
						
						
						if ($s['note'] == 70) {$whoiswho = "🥇";}
						elseif ($s['note'] == 20) {$whoiswho = "🔗";}
						elseif ($s['note'] == 150) {$whoiswho = "🏆";}
						else {$whoiswho = "";}
						
						if ($s['type'] == 1) { // Si c'est une fiche niveau 1 (Fiche dédiée) ?>
						
							<h2 class="titre_site"><a href="<?php echo $s['info']['permanlink']; ?>" class="reaject"><?php echo broken_html($s['titre']); ?></a> <?php if(!empty($whoiswho)) {echo $whoiswho;} ?></h2>
							
							<?php if ($s['url'] != "") { // Si la fiche à une URL ?>
							<div class="thumbsection">
				
								<a href="<?php echo $s['info']['permanlink']; ?>">
									<<?php echo $imgtag_vignette; ?> src="https://www.robothumb.com/src/?url=<?php echo $s['url']; ?>&size=320x240" alt="<?php echo stripslashes($s['titre']); ?>"><?php echo $imgtag_close; ?>
								</a>
				
							</div>
							
							<p class="descri_section"><?php echo strip_tags(htmlspecialchars_decode(stripslashes(cut_str($s['description'], 120)))); ?></p>
							
							<?php } else { // Si la fiche n'a pas d'url ?>

							<div class="thumbsection">
				
								<a href="<?php echo $s['info']['permanlink']; ?>">
									<<?php echo $imgtag_vignette; ?> src="/images/bigdefault.png" alt="<?php echo stripslashes($s['titre']); ?>"><?php echo $imgtag_close; ?>
								</a>
				
							</div>
							
							<p class="descri_section"><?php echo strip_tags(htmlspecialchars_decode(stripslashes(cut_str($s['description'], 120)))); ?></p>

				
							<?php } 
							
						} else { // Si c'est une fiche niveau 2 (Pas de fiche dédiée, liens depuis la section) ?>
									
							<h2 class="titre_site"><a href="<?php echo $s['url']; ?>" class="reaject"><?php echo broken_html($s['titre']); ?></a> <?php if(!empty($whoiswho)) {echo $whoiswho;} ?></h2>
				
							<?php if ($s['url'] != "") { // Si la fiche a une URL ?>
							<div class="thumbsection">
	
								<a href="<?php echo $s['url']; ?>">
									<<?php echo $imgtag_vignette; ?> src="https://www.robothumb.com/src/?url=<?php echo $s['url']; ?>&size=320x240" alt="<?php echo stripslashes($s['titre']); ?>"><?php echo $imgtag_close; ?>
								</a>
	
							</div>
							<?php } ?>	
				
							<p class="descri_section"><?php echo strip_tags(htmlspecialchars_decode(stripslashes(cut_str($s['description'], 1005)))); ?></p>
										
						<?php } ?>
										
						<div class="lurl">
							<?php if (($s['note'] >= 70) AND ($s['url'] != "")) {$alalaurl = $s['url']; echo '<a href="'.$alalaurl.'" target="_blank">';} ?>
								<?php echo parse_url($s['url'], PHP_URL_HOST); ?>
							<?php if (($s['note'] >= 70) AND ($s['url'] != "")) {echo "</a>";} ?>
						</div>
						
					</li>
								
					<?php
					
					// Module de gestion rapide
					if (isset($_SESSION['loggedin']) AND ($_SESSION['statut'] == "kaioh")) { 
					
					
						$lid = $s['id_site']; 
						$lagestion = $url_annuaire."gestion/?act=1&id=".$lid;
						$poubelle = $url_annuaire."gestion/?act=1&f=31&id=".$lid;
						?>
	
						<p class="fastadmin"><a href="<?php echo $s['url']; ?>" rel="nofollow" target="_blank">🌐 URL</a><?php if (!empty($s['url_retour'])) { ?> || <a href="<?php echo $s['url_retour']; ?>" rel="nofollow" target="_blank">🔗 BL</a><?php } ?> || <a href="<?php echo $lagestion; ?>" rel="nofollow" target="_blank">🛡️ Admin</a> || <a href="<?php echo $poubelle; ?>" onclick="return confirm('Etes-vous sur de vouloir supprimer ce site ?')" target="_blank" rel="nofollow">🗑️ Supprimer</a></p>
			
					<?php 
					
					// FIN Module de gestion rapide
					}  
					
				} 
					
			} 
	
?>
			
			</ul>
		</div>
	<?php } ?>

	<div style="clear:both;"></div>

</div>
				
<?php

include 'footer.php';
