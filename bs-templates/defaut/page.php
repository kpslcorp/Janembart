<?php
// Module pour empecher le include d'être appelé directement
if (empty($variable_temoin))
{
exit("Quelque-chose me dit que vous n'avez rien à faire ici ?!");
} 
// Module pour empecher le include d'être appelé directement

$id_page = intval($_GET['id']);
$testexistenciel = rcp_page($id_page, "");

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

$id_page = intval($_GET['id']);
		
		$page = rcp_page($id_page, "");
		
		foreach($page as $p){
			
		?>
		
	<div id="breadcrumb">
		<p id="fil_ariane">
			<a href="<?php echo $url_annuaire; ?>">Annuaire</a> &gt; <?php echo stripslashes($p['titre']); ?>
		</p>
	</div>
	
	<div class="align_center"><h1 class="color_titre"><?php echo stripslashes($p['titre']); ?></h1></div>
	
	<div class="page_annu">
	
		<?php 
			
			if ($amp == true) { 
			
				$intelli_text = stripslashes($p['contenu']);
				$intelli_text = preg_replace('/(<img[^>]+>(?:<\/img>)?)/i', '$1</amp-img>', $intelli_text);
				$intelli_text = str_replace('<img', '<amp-img layout="responsive" width="700" height="466"', $intelli_text);
				echo $intelli_text;
			
			} else {
			
				echo stripslashes($p['contenu']); 
			
			}
			
		?>
		
	</div>
						
<?php 	} ?>

</div>

<?php

include 'footer.php';

?>