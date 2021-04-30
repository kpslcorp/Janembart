<?php

// Module pour empecher le include d'etre appelé directement
if (empty($variable_temoin))
{
exit("Quelque-chose me dit que vous n'avez rien à faire ici ?!");
} 
// Module pour empecher le include d'etre appelé directement

$idee_cat = intval($_GET['id']);
$testexistenciel = rcp_cat($idee_cat, "");

if (empty($testexistenciel))
{
header("Status: 301 Moved Permanently", false, 301);
header("Location: $url_annuaire");
exit();
} 

include 'header.php'; 

?>

<div id="page_content">

<?php

$id_cat = intval($_GET['id']);
		
$cat = rcp_cat($id_cat, "");
		
$c = $cat[0];

?>
		
<div id="breadcrumb">
	<p id="fil_ariane">
		<a href="<?php echo $url_annuaire; ?>">Annuaire</a> &gt; <?php echo broken_html($c['titre']); ?>	
	</p>	
</div>
	
<div class="align_center">
	<h1 class="color_titre">Sites <?php echo broken_html($c['titre']); ?></h1>
	<div class="description_annu"><p><?php echo stripslashes($c['description']); ?></p></div>
</div>	
		<div id="j_category">
			<?php 
			
			$sect = rcp_sect("", $c['id_cat'], "ORDER BY titre ASC");

			foreach($sect as $se){
			
			?>
				<div class='lasection'>
					<h2>🔱 <a href="<?php echo $se['info']['permanlink']; ?>" class="titre_site"><?php echo stripslashes($se['titre']); ?></a></h2>

					<p><?php echo stripslashes(cut_str($se['description'], 180)); ?></p>
					
					
					<?php 
					$site = '';
			
					$site = rcp_site("", $se['id_sect'], "ORDER BY compteur ASC", 1, 1, "LIMIT 0, 3");
					if(is_array($site)) {
						echo "<p>🔥 Les + récents : ";
						foreach($site as $s){
							$site.='<a href="'.$s['info']['permanlink'].'">'.url_www($s['url']).'</a>, ';	
						}
					
					$site = @substr($site,0,-2); 
					echo @substr($site,5); 
					echo "</p>";
					}
					else {echo "<p><a href='ajouter.html'>🆕 Votre site ici</a></p>";}
					?>
				</div>
			<?php } ?>
		</div>

	<div style="clear:both;"></div>
</div>
				
<?php

include 'footer.php';

?>