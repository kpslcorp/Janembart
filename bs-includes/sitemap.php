<?php
if (htmlspecialchars($_GET['format']) == 'xml'){
echo '<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';	
?>			
<?php

	if (intval($_GET['c']) == '1'){
	
	?>
	
		<url>
		
			<loc><?php echo $url_annuaire; ?></loc>
			<priority>1</priority>
			
		</url>
		
		
		<url>
		
			<loc><?php echo $url_annuaire; ?>sitemap/sitemap-2.xml</loc>
			<priority>0.5</priority>
			
		</url>
		
		<url>
		
			<loc><?php echo $url_annuaire; ?>sitemap/sitemap-3.xml</loc>
			<priority>0.5</priority>
			
		</url>
		
		<url>
		
			<loc><?php echo $url_annuaire; ?>sitemap/sitemap-4.xml</loc>
			<priority>0.5</priority>
			
		</url>
		
		<url>
		
			<loc><?php echo $url_annuaire; ?>sitemap/sitemap-5.xml</loc>
			<priority>0.5</priority>
			
		</url>
	
	<?php
	
	}
	elseif (intval($_GET['c']) == '2'){
	
	$site = rcp_site("", "", "ORDER BY note DESC", 1, 1, "");
	
		foreach($site as $s){
		
			$sect = rcp_sect($s['sect'], "", "");
			
				foreach($sect as $se){
					
					$cat = rcp_cat($se['id_cat'], "");
					
						foreach($cat as $c){
	
	?>
		<url>
		
			<loc><?php echo $url_annuaire.clean($c['titre']).'/'.clean($se['titre']).'/1.'.$s['id_site'].'-'.clean($s['titre']).$extension; ?></loc>
			<priority>0.6</priority>
			
		</url>
	
	
	<?php
						}
	
				}
			
		}
	
	}
	elseif (intval($_GET['c']) == '3'){
	
		$sect = rcp_sect($s['sect'], "", "");
		
			foreach($sect as $se){
			
				$cat = rcp_cat($se['id_cat'], "");
				
					foreach($cat as $c){
					
					?>
					
		<url>
		
			<loc><?php echo $url_annuaire.clean($c['titre']).'/2.'.$se['id_sect'].'-'.clean($se['titre']).$extension; ?></loc>
			<priority>0.7</priority>
			
		</url>
					
					<?php
					
					}
	
			}
	
	}
	
	elseif (intval($_GET['c']) == '4'){
	
		$cat = rcp_cat("", "");
		
			foreach($cat as $c){
			
			?>
					
		<url>
		
			<loc><?php echo $url_annuaire.'3.'.$c['id_cat'].'-'.clean($c['titre']).$extension; ?></loc>
			<priority>0.8</priority>
			
		</url>
					
					<?php
			
			}
	
	}
	
	elseif (intval($_GET['c']) == '5'){
	
		$page = rcp_page("", "");
		
			foreach($page as $p){
			
			?>
					
		<url>
		
			<loc><?php echo $url_annuaire.'4.'.$p['id_page'].'-'.clean($p['titre']).$extension; ?></loc>
			<priority>0.9</priority>
			
		</url>
					
					<?php
			
			}
	
	}
	
?>

	</urlset> 
	
<?php
	
}

elseif (htmlspecialchars($_GET['format']) == 'html'){
?>
<!doctype html>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<meta name="generator" content="Janembart X Bartemis" />
	<title><?php echo url_www($url_annuaire); ?> : plan du site</title> 
	<link rel="stylesheet" type="text/css" href="<?php echo $url_annuaire; ?>bs-templates/defaut/style.css">     
</head>

<body style="width:900px">
	<div>
<?php

	if (intval($_GET['c']) == '1'){
	
	?>
	<ul>
		<li>
			<a href="<?php echo $url_annuaire; ?> ">Annuaire <?php echo $titre_annuaire; ?></a>
		</li>
	</ul>
	
	<ul>
		<li><a href="<?php echo $url_annuaire; ?>sitemap/sitemap-2.html">Sitemap html Site</a></li>
		<li><a href="<?php echo $url_annuaire; ?>sitemap/sitemap-3.html">Sitemap html Section</a></li>
		<li><a href="<?php echo $url_annuaire; ?>sitemap/sitemap-4.html">Sitemap html Catégorie</a></li>
		<li><a href="<?php echo $url_annuaire; ?>sitemap/sitemap-5.html">Sitemap html Page</a></li>
	</ul>
	
	<ul>
		<li><a href="<?php echo $url_annuaire; ?>sitemap/sitemap-1.xml">Sitemap xml Index</a></li>
		<li><a href="<?php echo $url_annuaire; ?>sitemap/sitemap-2.xml">Sitemap xml Site</a></li>
		<li><a href="<?php echo $url_annuaire; ?>sitemap/sitemap-3.xml">Sitemap xml Section</a></li>
		<li><a href="<?php echo $url_annuaire; ?>sitemap/sitemap-4.xml">Sitemap xml Catégorie</a></li>
		<li><a href="<?php echo $url_annuaire; ?>sitemap/sitemap-5.xml">Sitemap xml Page</a></li>	
	</ul>
	
	<?php
	
	}
	
	elseif (intval($_GET['c']) == '2'){
	
		?><ul><?php
	
	$site = rcp_site("", "", "ORDER BY note DESC", 1, 1, "");
	
		foreach($site as $s){
		
			$sect = rcp_sect($s['sect'], "", "");
			
				foreach($sect as $se){
					
					$cat = rcp_cat($se['id_cat'], "");
					
						foreach($cat as $c){
	
	?>
		
		
			<li><a href="<?php echo $url_annuaire.clean($c['titre']).'/'.clean($se['titre']).'/1.'.$s['id_site'].'-'.clean($s['titre']).$extension; ?>"><?php echo stripslashes($s['titre']); ?></a></li>
	
	
	<?php
						}
	
				}
			
		}
		?></ul><?php
	
	}
	
	elseif (intval($_GET['c']) == '3'){
	
		?><ul><?php
	
		$sect = rcp_sect($s['sect'], "", "ORDER by id_cat DESC");
		
			foreach($sect as $se){
			
				$cat = rcp_cat($se['id_cat'], "");
				
					foreach($cat as $c){
					
					?>
					
			<li><a href="<?php echo$url_annuaire.clean($c['titre']).'/2.'.$se['id_sect'].'-'.clean($se['titre']).$extension; ?>"><?php echo stripslashes($c['titre'].' » '.$se['titre']); ?></a></li>
										
					<?php
					
					}
	
			}
			
		?></ul><?php
	
	}
	
	elseif (intval($_GET['c']) == '4'){
	
		echo "<ul>";
		$cat = rcp_cat("", "");
		
			foreach($cat as $c){
			
			?>
			
			<li><a href="<?php echo $url_annuaire.'3.'.$c['id_cat'].'-'.clean($c['titre']).$extension; ?>"><?php echo stripslashes($c['titre']); ?></a></li>
					
			<?php
			
			}
		echo "</ul>";
	
	}
	
	elseif (intval($_GET['c']) == '5'){
	
		$page = rcp_page("", "");
		
			echo "<ul>";
			foreach($page as $p){
			
			?>
					
		<li><a href="<?php echo $url_annuaire.'4.'.$p['id_page'].'-'.clean($p['titre']).$extension; ?>"><?php echo stripslashes($p['titre']); ?></a></li>	
			<?php
			
			}
			echo "</ul>";
	
	}
	
?>
	</div>
</body>
</html>

<?php
	
}	