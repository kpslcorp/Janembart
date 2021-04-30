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
	header('Location: '.$url_annuaire.'updatemywebsite.html');
}
	

if (isset($_SESSION['loggedin'])){ 
		
		$id_user = $_SESSION['id_user'];
		$mail_user = $_SESSION['mail_user'];
		$id_du_site_sur_le_billard = $_POST['id_site'];
}
	
else {
		
		exit ("Erreur, merci de vous reconnecter.");
		
}
	
if(!isset($id_du_site_sur_le_billard)) {exit('Wait wait wait, on parle de quel site la ?');} 


include 'header.php';


$site = rcp_site(intval($id_du_site_sur_le_billard), '', '', '', '', '');

if ($site[0]["mail_auteur"] != $mail_user) {$qqchcloche = "Vous n'avez pas la possibilité de modifier ce site. Merci de <a href='contact.html'>contacter un administrateur</a> pour plus d'informations.";}
elseif ($site[0]["valide"] == 2) {$qqchcloche = "Ce site n'a pas encore été validé. Patience !";}
else {$qqchcloche = "";} // Non tout va bien, on peut lancer la machine !

if ($qqchcloche != "") {
	
	echo "<h1>Erreur</h1><p>$qqchcloche</p>";
	
} else {
	
	echo '<div id="page_content" class="pader">';
							
	foreach($site as $s){
	
		$sect = rcp_sect($s['sect'], "", "");
						
			foreach($sect as $se){
						
				$cat = rcp_cat($se['id_cat'], "");
						
					foreach($cat as $c){

									?>


	<?php 
		if ($s['note'] <= 20) { // Booster
		?>
	
					<form action="boostmywebsite.html" method="POST" onsubmit="return confirm('Voulez vous vraiment booster ce site en mode PRO contre 1 crédit ?');">
					
						<input type="hidden" name="id_site" value="<?php echo $s['id_site']; ?>" />
						<input type="submit" value="📧 Booster ce site (1 crédit) 🔺" ></button>
						
					</form>

		<?php } else { ?>
		
		<p><span class="conseil" style="text-align:center;">🏆 Ce site est déjà boosté au plus haut niveau 🏆</span></p>
		
		<?php } ?>
	<form method="POST" action="websiteupdated.html">

	<fieldset id="ajouter2">
	<table width="100%" class="formulaire" style="margin-top:20px;">
		<tr>
		
			<td class="align_left" width="20%">
			
				<label>📅 Validé le :</label></td>
				
			<td>
			
					<?php $v_date = new DateTime($s['v_date']); echo " ☑️ ". $v_date->format('d/m/Y');  ?>

			</td>
				
		</tr>
				
		<tr style="line-height:30px;">
		
			<td class="align_left" width="25%">
			
				<label for="mail_auteur">🌍 Fiche Site :</label>
				
			</td>
			<td>
			
				<a href="<?php echo $s['info']['permanlink']; ?>" target="_blank">Voir sur l'annuaire</a>
				
			</td>
			
		</tr>
			
		<tr>
		
			<td colspan="2"><span class="conseil poptitre">Le titre du site sera utilisé en H1 et &lt;title> de votre fiche site</span></td>
		</tr>
		
		<tr>
			
			<td class="align_left" width="25%">
				
				<label for="url">📛 Titre du site :</label>
				
			</td>
			
			<td>
			
				<input value="<?php echo stripslashes($s['titre']); ?>" type="text" name="titre" style="width : 400px" class="soumettre_input" />
				
			</td>
			
		</tr>
	
		
		<tr>
			
			<td class="align_left" width="25%">
    
				<label for="url">🌐 URL du site :</label></td>
			
			<td>
			
				<input value="<?php echo $s['url']; ?>" type="text" name="url" style="width : 400px" class="soumettre_input" /> <a href="<?php echo $s['url']; ?>" target="_blank"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEAAAABACAYAAACqaXHeAAACf0lEQVR4Xu2av04VQRSHPyTRWPgAJCa2QKT3HRQLhBYSwhtoCw01vY3RzkKEAnkHekKoKKx8ACtM/JOT7JrNZnZ3ZnfO3bOzc5tb3Nmz5/fNOb+duTtLzPyzNHP9ZAC5AmZOILfAzAsgm2BICzwDjoAXwCrwIFL1uHI4Bd4oxv8f2hfAFvAJeBIpqWoY8wBk5q+VxAsI8wA+APsKM1+GNA/gBlifM4DfDsM7Aa4iQRHDq3/EaJ92xH8F7Hnk0OpzPib413GTHcCVuEc+UYa8Br4ADz2iJQcgRHyTyQY9Bi1VQKj4pAC0if8FfG7whCRaoEv8NvCo8IW6LUwewGZhuC7Dk5kX8RfFtxhjUgB8xYtoAZEUgBDxyQEIFZ8UgC7xsk3+5uj1JFqgr/gkKmCI+MkDkI3N14a1vTzqmsq+2gmygXrX0BqNWwYLm6EY4j32RO4hYwMYVXznRqFgprUZGl38mABMiB8LQJd4+Qf6sndTB164aA/QFG9+IfQSOGt51A2dedMAtMWbXggtQrxZAG3i74sVXizDM9cCixRvrgIWLd4UgBXgDnjseCTHLvvqLUy1wC7wsfZKTVO8qQooZ6UKQVu8SQCSlEB4D8i7xFhu37TYNdUC1STFE34ELtH7DDcLoI+YPtdkAKm9GAmtglwBuQISezdopgW0D0mFCm0aL+8F3tZ+/AMst93A5y8x7WNysQC44kjuz4cC0D4oqQlAcj8YCkD7qKwWgJ/ABvB9KAC5XvOwtAYAES+HKM+7gvt4QBlDKuGwOC6/FvG4fFeOvr+L4d0WJ1iPu2a+DBoCwDeRSY3LACY1XQrJ5gpQgDqpkLkCJjVdCsnOvgL+Ac3X1EEJkLOsAAAAAElFTkSuQmCC" width="15" alt="Ouvrir le lien"/></a>
			
			</td>
			
		</tr>
		
		<tr>
		
			<td class="align_left" width="20%">
	
				<label for="url">🔗 Ancre du lien :</label></td>
				
			<td>
 
				<input value="<?php echo $s['ancre']; ?>" type="text" name="ancre" style="width : 400px" class="soumettre_input" />
				
			</td>
			
		</tr>
		
		<tr>
		
			<td class="align_left" width="20%">
	
				<label for="url"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAACGUlEQVQ4T53TX0hTURzA8e/d3XbnxLUlpaOkFZW0IQqJkQ8t6GHuodAX3YOi/aGQeqgnrd4iCOrVpIdhovUWIiQsoX9oPkSFPdjIbGrDJ3Pq5pzc/bk3NuWWNkI7T4dzzu9zfr8f5wg/b5a6VUV4jMBBdjZmUWkV5jvs0/8RvH6Vyoww32lXs3PDgWr0ZcdRFn+QCn9EiS9sKx8NMJ++RqHn1oaskpoeIzH6iOTk639CGmAsP4Op2oe+pBxxz2EtKPn1JSvPbqCsRvJCGvDnrljsoKD2EgUnWkA0kFmeI+pvJBOZ/QvRAKmqAaniHOm5ceTxgVyQ3u7C0tKDaCsjsxRmucuLkljahOTvgZJmbcxPfPgeusLdWNufI1r3I38JEHtyMT8g7j2K8YgbyenBcKg2dyj57S3Rvlb0die29iHQiSz7G0mF3mlI3h5IzjqKmroQjGYSI92sBu5S1HAfU00zyclXRHtb8gCCgFRxFjJp5GAAyenF0uyHTIrIg5PoTEXYrr8BJc3CHReqvJJDfjexsh6Lrzu3GOs/jxwcxnplEIOjJpdBNpPijg/orPuI9vhITo1sAaoasDQ93AAuIAdfYHZfpbDuNvLEELGnl9nV1k/2vcQHO1l737cZIFtCZf16CRNDoKroS49hdHrILIaRPw8gubyIJeWkvo+SCn/SgBnAsa2Hv/WQQCj7G08BvTv+kQIhFKHtFxlX1+he0M78AAAAAElFTkSuQmCC"> URL Flux RSS :</label></td>
				
			<td>
 
				<input value="<?php echo $s['url_rss']; ?>" type="text" name="url_rss" style="width : 400px" class="soumettre_input" />  <?php if (!empty($s['url_rss'])) {?><a href="<?php echo $s['url_rss']; ?>" target="_blank"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEAAAABACAYAAACqaXHeAAACf0lEQVR4Xu2av04VQRSHPyTRWPgAJCa2QKT3HRQLhBYSwhtoCw01vY3RzkKEAnkHekKoKKx8ACtM/JOT7JrNZnZ3ZnfO3bOzc5tb3Nmz5/fNOb+duTtLzPyzNHP9ZAC5AmZOILfAzAsgm2BICzwDjoAXwCrwIFL1uHI4Bd4oxv8f2hfAFvAJeBIpqWoY8wBk5q+VxAsI8wA+APsKM1+GNA/gBlifM4DfDsM7Aa4iQRHDq3/EaJ92xH8F7Hnk0OpzPib413GTHcCVuEc+UYa8Br4ADz2iJQcgRHyTyQY9Bi1VQKj4pAC0if8FfG7whCRaoEv8NvCo8IW6LUwewGZhuC7Dk5kX8RfFtxhjUgB8xYtoAZEUgBDxyQEIFZ8UgC7xsk3+5uj1JFqgr/gkKmCI+MkDkI3N14a1vTzqmsq+2gmygXrX0BqNWwYLm6EY4j32RO4hYwMYVXznRqFgprUZGl38mABMiB8LQJd4+Qf6sndTB164aA/QFG9+IfQSOGt51A2dedMAtMWbXggtQrxZAG3i74sVXizDM9cCixRvrgIWLd4UgBXgDnjseCTHLvvqLUy1wC7wsfZKTVO8qQooZ6UKQVu8SQCSlEB4D8i7xFhu37TYNdUC1STFE34ELtH7DDcLoI+YPtdkAKm9GAmtglwBuQISezdopgW0D0mFCm0aL+8F3tZ+/AMst93A5y8x7WNysQC44kjuz4cC0D4oqQlAcj8YCkD7qKwWgJ/ABvB9KAC5XvOwtAYAES+HKM+7gvt4QBlDKuGwOC6/FvG4fFeOvr+L4d0WJ1iPu2a+DBoCwDeRSY3LACY1XQrJ5gpQgDqpkLkCJjVdCsnOvgL+Ac3X1EEJkLOsAAAAAElFTkSuQmCC" width="15" alt="Ouvrir le lien"/></a><?php } ?>
				
			</td>
			
		</tr>

		<tr>
		
			<td class="align_left" width="20%">
			
				<label for="url">🗨️ Catégorie :</label></td>
				
			<td>
			
				<select name="sect" style="width: 400px;">
					<?php 
					
					$cat2 = rcp_cat("", "ORDER BY titre ASC");  
					
						foreach($cat2 as $c2){
						
						$sect2 = rcp_sect("", $c2['id_cat'], "ORDER BY titre ASC"); 
						
							foreach($sect2 as $se2){
							?>
					
					<option value="<?php echo $se2['id_sect']; ?>" <?php if ($se2['id_sect'] == $s['sect']) { echo 'selected'; } ?>><?php echo stripslashes($c2['titre']); ?> >> <?php echo stripslashes($se2['titre']); ?></option>
					
							<?php }
							
						} ?>
				</select>
				
			</td>
			
		</tr>
		
		<tr>
		
			<td colspan="2">
			<p><span class="conseil" style="text-align:center;">Vous devez fournir une <u>description unique</u> d'au moins <?php echo $nb_cara_description; ?> caractères OBLIGATOIREMENT !</span></p>
			<p>✅ Balises HTML autorisées : P - H2/H3/H4/H5 - Strong - Em - ul/ol/li - Blockquote<br />✅ Import depuis Word avec nettoyage du code HTML activé (à vérifier)<br />⚠️ La balise H1> est interdite car réservée au titre de votre fiche.<br />⚠️ Les balises IMG et A sont également interdites.<br /> ⚠️ Les attributs seront automatiquement supprimés (style="" / class="" / id="" / src="" ...)</p>
			</td>
			
		</tr>
	

		<tr>
		
			<td colspan="2"><textarea class="soumettre_textarea " name="description" id="mytextarea"><?php echo convert_br(stripslashes($s['description']), 1); ?></textarea></td>
			
		</tr>
		
		
		
		
		<tr>
		
			<td colspan="2"><p style="text-align:center"><input value="<?php echo $s['id_site']; ?>" name="id" type="hidden" style="margin-top: 3px;" /><input value="Valider la modification" type="submit" style="margin-top: 3px;" /></p></td>
			
		</tr>
		</table>
		</fieldset>
		</form>
		
	

</div>

<?php /* Editeur WYSIWYG */ ?>	
<script src="//ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="<?php echo $url_annuaire; ?>js/trumbowyg/trumbowyg.min.js"></script>

<?php // Plugins WYSIWYG : Attention à bien intégrer les versions MIN en JS /!\ ?>
<script src="<?php echo $url_annuaire; ?>js/trumbowyg/plugins/history/trumbowyg.history.min.js"></script>
<script src="<?php echo $url_annuaire; ?>js/trumbowyg/plugins/cleanpaste/trumbowyg.cleanpaste.min.js"></script>
<script src="<?php echo $url_annuaire; ?>js/trumbowyg/plugins/allowtagsfrompaste/trumbowyg.allowtagsfrompaste.min.js"></script>
<script src="<?php echo $url_annuaire; ?>js/trumbowyg/plugins/trumbowyg-counter.js"></script>

<link rel="stylesheet" href="<?php echo $url_annuaire; ?>js/trumbowyg/trumbowyg.min.css">
<script>$('#mytextarea').trumbowyg({
svgPath: '<?php echo $url_annuaire; ?>js/trumbowyg/icons.svg',
btns: [
        ['viewHTML'],
        ['historyUndo', 'historyRedo'],
        ['p', 'h2', 'h3', 'h4', 'h5','blockquote'],
        ['strong', 'em'],
        ['unorderedList', 'orderedList'],
        ['removeformat'],
        ['fullscreen']
    ],
 plugins: {
        allowTagsFromPaste: {
            allowedTags: ['h2', 'h3', 'h4', 'h5', 'p', 'br', 'strong', 'em']
        }
    }	
});</script>
<style>.trumbowyg-box .trumbowyg-editor{font-family:verdana!important;}.trumbowyg-button-pane button{width:30px;}.trumbowyg-counter{background: #000; padding:9px 5px;font-size: 13px;line-height: 18px;text-transform:uppercase;color:white;}.chars-counter{margin-left:6px;}</style>
<?php /* Editeur WYSIWYG */ ?>	

<?php

	}}}}
		
include 'footer.php';

?>