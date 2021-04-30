<?php

// Module pour empecher le include d'être appelé directement
if (empty($variable_temoin))
{
exit("Quelque-chose me dit que vous n'avez rien à faire ici ?!");
} 
// Module pour empecher le include d'être appelé directement

$home = "yes";
include 'header.php'; 

?>

<div id="page_content">

	
		<h1>Bienvenue sur <?php echo $titre_annuaire; ?></h1>
		
		<div class="description_annu">
			<p class="align_center"><?php echo $description_annuaire; ?></p>
		</div>
		
	<h3>🗨️ Catégories du site <?php echo $titre_annuaire; ?></h3>
	
		<div id="jh_recat">
		<?php

			$cat = rcp_cat($id_cat, 'ORDER by titre ASC');
			
			foreach($cat as $c){

		?>
						<div class="jh_minicat">
						
							<h3><a href="<?php echo $c['info']['permanlink']; ?>" class="titre_site"><?php echo stripslashes($c['titre']); ?></a></h3>
							
							<span class="jh_lien_section">
							<?php
							
							$sect = rcp_sect( 0, $c['id_cat'], 'ORDER BY compteur DESC LIMIT 0, 6');
							
							$last = end($sect); 
							
								foreach($sect as $se){
							
									if ($se == $last)
										echo ' <a href="'.$se['info']['permanlink'].'" class="lien_section">'.stripslashes($se['titre']).'</a>...';
									else
										echo ' <a href="'.$se['info']['permanlink'].'" class="lien_section">'.stripslashes($se['titre']).'</a>, ';
							
								}
							

							?>
							
							</span>
							
						</div>
		<?php } ?>
		</div>
		
	<h3>🔥 Derniers sites inscrits sur <?php echo $titre_annuaire; ?></h3>
		
		<ul id="lastsite">
         
        <?php
            
               $site = rcp_site("", "", "ORDER BY id_site DESC", 1, 1, "LIMIT 6");
                     
                     foreach($site as $s){
		?>
         
							<li>
								<a href="<?php echo $s['info']['permanlink']; ?>" title="<?php echo stripslashes($s['titre']); ?>" class="shine">
										<<?php echo $imgtag_vignette; ?> src="https://www.robothumb.com/src/?url=<?php echo $s['url']; ?>&size=320x240" alt="<?php echo stripslashes($s['titre']); ?>" ><?php echo $imgtag_close; ?>
								</a>
							</li>
            
        <?php                             
               
					}
        ?>

		</ul>
	  
	  
	 <?php if (!empty($incontournables)) { ?>
	<h3>🤩 Sites incontournables en <? setlocale (LC_TIME, 'fr_FR.utf8','fra'); echo (strftime("%B")); ?> <?php echo date('Y'); ?></h3>
	  
		<ul id="incontournables">
		
		
			<?php foreach($incontournables as $inc) { 	?>
         
			<?php
            
               $linc = rcp_site($inc, "", "", "", "", "");
               
                     ?>
         
							<li>
								<a href="<?php echo $linc[0]['info']['permanlink']; ?>" title="<?php echo stripslashes($linc[0]['titre']); ?>" class="shine">
										<<?php echo $imgtag_vignette; ?> src="https://www.robothumb.com/src/?url=<?php echo $linc[0]['url']; ?>&size=320x240" alt="<?php echo stripslashes($linc[0]['titre']); ?>" ><?php echo $imgtag_close; ?>
								</a>
							</li>
            
              
			<?php } ?>
			
		</ul>
	<?php } ?>
	
	<?php if (!empty($coupdecoeur)) { ?>
	<h3>💖 Coups de coeur du mois de <? setlocale (LC_TIME, 'fr_FR.utf8','fra'); echo (strftime("%B")); ?> <?php echo date('Y'); ?></h3>

		<ul id="coup2coeur">
				<?php foreach($coupdecoeur as $s){ ?>
							<li>
								<a href="<?php echo $s; ?>" title="<?php echo $s; ?>" target="_blank" rel="noopener" class="shine">
										<<?php echo $imgtag_vignette; ?> src="https://www.robothumb.com/src/?url=<?php echo $s; ?>&size=320x240" alt="<?php echo $s; ?>" ><?php echo $imgtag_close; ?>
								</a>
							</li>
				<?php } ?>
		</ul>
		
		<p class="align_center" style="background:yellow;color:black;font-size:20px;">⚡ Un Lien Do-Follow direct vers votre site ici ? <a href="contact.html">Contactez-nous vite</a></p>
	<?php } ?>
	
	<h3>😎 Sites les + populaires sur <?php echo $titre_annuaire; ?></h3>
		
		<ul id="popstars">
         
        <?php
            
               $site = rcp_site("", "", "ORDER BY compteur DESC", 1, 1, "LIMIT 6");
                     
                     foreach($site as $s){
		?>
         
							<li>
								<a href="<?php echo $s['info']['permanlink']; ?>" title="<?php echo stripslashes($s['titre']); ?>" class="shine">
										<<?php echo $imgtag_vignette; ?> src="https://www.robothumb.com/src/?url=<?php echo $s['url']; ?>&size=320x240" alt="<?php echo stripslashes($s['titre']); ?>" ><?php echo $imgtag_close; ?>
								</a>
							</li>
            
        <?php                             
               
					}
        ?>

		</ul>
</div>

<?php

include 'footer.php';

?>