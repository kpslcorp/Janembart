<?php 

// Module pour empecher le include d'être appelé directement
if (empty($variable_temoin))
{
exit("Quelque-chose me dit que vous n'avez rien à faire ici ?!");
} 
// Module pour empecher le include d'être appelé directement

include 'right-colonne.php'; ?>
		
	</div>
		<div id="footer">
		
			<?php

			// Merci de ne pas retirer, consulter les conditions d'utilisation
			
			echo copyright(); 
			
			?> 

		</div>
		
		<?php if ($amp == true) { ?>
			<?php if (!empty($google_analytics)) { ?>
				 <amp-analytics type="gtag" data-credentials="include">
					<script type="application/json">
					{
					  "vars" : {
						"gtag_id": "<?php echo $google_analytics; ?>",
						"config" : {
						  "<?php echo $google_analytics; ?>": {
							"groups": "default"
						  }
						}
					  }
					}
					</script>
					</amp-analytics>
			<?php } ?>
		<?php } ?>
</body>

</html>