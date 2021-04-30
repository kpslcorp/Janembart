<?php

// Module pour empecher le include d'être appelé directement
if (empty($variable_temoin))
{
exit("Quelque-chose me dit que vous n'avez rien à faire ici ?!");
} 
// Module pour empecher le include d'être appelé directement

$id_sect = intval($_GET['id']);
$sect = rcp_sect($id_sect, "", "");	

$se = $sect[0];
$pt = intval($_GET['p']);
$nb_total = count_page($se['id_sect']);
$current_permalink = $se['info']['permanlink'];

$limit = $nb_fiche_section;
$page = isset($pt) ? $pt : ''; 

if ($nb_total > $limit) {
			$i=0; $j=1;
			
			if($nb_total>$limit) {
			$bread2k2k = "";
			
				while($i<($nb_total/$limit)) {
				
					if($i!=$page){
						$bread2k2k .= '<a href="'.$current_permalink.'?p='.$i.'" class="navp" title="'.$titre_c.'">'.$j.'</a>';}
					
					else {
						$bread2k2k .= '<b class="active">'.$j.'</b>';
					}
					
					$i++;$j++;
				
				}
			}

								
	if (!empty($pt))
	{
		$page = $pt;					
		$tortueninja = true;	
	}	
	


$smart_p = $pt+1;
$smart_r = $j-1;

	if ($smart_p > $smart_r)
	{

		header("Status: 301 Moved Permanently", false, 301);
		header("Location: $current_permalink");
		//exit();
	}
}

$testexistenciel = $sect;
if (empty($testexistenciel)) 
{
	header("Status: 301 Moved Permanently", false, 301);
	header("Location: $url_annuaire");
	exit();
} 
else {	
	include 'header.php';	
}
?>

<div id="page_content">
	<div id="breadcrumb">
			<p id="fil_ariane" itemscope itemtype="https://schema.org/BreadcrumbList">
										<span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
											<a href="<?php echo $url_annuaire; ?>" rel="home" itemtype="https://schema.org/Thing" itemprop="item"><span itemprop="name"><?php echo $titre_annuaire; ?></span></a><meta itemprop="position" content="1" />
										</span> &raquo;
										<span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
											<a href="<?php echo $se['info']['cat']['permanlink']; ?>" title="<?php echo stripslashes($se['info']['cat']['titre']); ?>" itemtype="https://schema.org/Thing" itemprop="item"><span itemprop="name"><?php echo stripslashes($se['info']['cat']['titre']); ?></span></a><meta itemprop="position" content="2" />
										</span> &raquo;
										<?php echo stripslashes($se['titre']); ?>
			</p>
		</div>
						
	<div class="align_center">
	
		<h1 class="color_titre">Sites <?php echo stripslashes($se['titre']); ?></h1>
		
	
	<?php if (($se['description']) != "") {?><div class="description_annu"><p><?php echo stripslashes($se['description']); ?></p></div><?php } ?>
	
	<?php $site = rcp_site("", $se['id_sect'], "", 1, "", ""); // Determine si on a au moins un site dans cette section ?>
	
	<?php 
		if 		($sort4site == 'new2old')	 {$instructionsql = "ORDER BY id_site DESC";}
		elseif 	($sort4site == 'old2new')	 {$instructionsql = "ORDER BY id_site ASC";}
		elseif 	($sort4site == 'note') 		 {$instructionsql = "ORDER BY note DESC, titre ASC";}
		elseif 	($sort4site == 'az') 		 {$instructionsql = "ORDER BY titre ASC";}
		else 							 	 {$instructionsql = "ORDER BY note DESC";}
	?>
	
	<?php if(!empty($site)) { // Si on a au moins un site, on déroule...?>	
	<div>
	
		<ul class="liste_site">
		<?php
			if (isset($pt)) {
			
				$page_d = $pt*$nb_fiche_section;
				$page_f = $nb_fiche_section;
			
				$site = rcp_site("", $se['id_sect'], $instructionsql, 1, "", "LIMIT $page_d, $page_f");
			
			} 
			else {
			
				$site = rcp_site("", $se['id_sect'], $instructionsql, 1, "", "LIMIT 0, $nb_fiche_section");
			
			}
		
			if(is_array($site)) {
			foreach($site as $s){
		
			if($modulo % 2 == 0) { echo '<li>'; } else { echo '<li class="site_modulo">'; } $modulo++; ?>
			
			<?php // MEA des Sites Pros
			
			
			if ($s['note'] == 70) {$whoiswho = "🥇";}
			elseif ($s['note'] == 20) {$whoiswho = "🔗";}
			elseif ($s['note'] == 150) {$whoiswho = "🏆";}
			else {$whoiswho = "";}
			
			
			?>
			
			
			<?php	if ($s['type'] == 1) { // Si c'est une fiche niveau 1 (Fiche dédiée) ?>
						
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
							
									}	 
									
									else // Si c'est une fiche niveau 2 (Pas de fiche dédiée, liens depuis la section)
									
									{  ?>
						
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
			}  ?>
	
		
					<?php } 
					
					} 
					?>
			
			</ul>
		
	</div>

	<?php } else { ?>
<p>😭 Aucun site n'a pour le moment retenu notre attention 😭</p>
<p><a href="<?php echo $url_annuaire; ?>ajouter.html">🗨️ Proposer mon site <<</a></p>

<?php } ?>
	
	<div class="navp boom">
		<?php echo $bread2k2k; ?>	
	</div>
</div>
</div>
<?php

include 'footer.php';

?>