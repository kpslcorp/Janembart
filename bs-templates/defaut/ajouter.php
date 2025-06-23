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
	header('Location: '.$url_annuaire.'ajouter.html');
	}
include 'header.php';
?>
	
	
<?php // MODULE FASTPASS (Admin / Compte Pro)

		if (isset($_SESSION['loggedin'])){ 
	
		$fastpass = "yes";	
		
		if (($_SESSION['statut'] == "pr0") AND ($coins <= 0)) {$credit_epuise = "yes";}
		if ($_SESSION['statut'] == "kaioh") {$nb_cara_description = 0;} // Pas de minimums pour l'admin
		
		
		}
		// FIN DU MODULE
?>
		 
<div id="page_content">
	<h1>✅ Proposer un site</h1>
	
	<div id="breadcrumb">
	<p id="fil_ariane">
		<a href="<?php echo $url_annuaire; ?>">Annuaire</a> &gt; Ajouter un site
	</p>	
	</div>
	
	<?php 
		if (($payant_only == true) && ($fastpass != "yes")) { ?>
			<p>Pour soumettre un nouveau site vous devez avoir un compte pro. Si vous en avez déjà un, connectez-vous, sinon <a href="/acces-pro/creation-pro.php">inscrivez-vous ici</a>.</p>
	<?php } else { ?>
	
	<?php if ($credit_epuise == "yes") { ?>
		
		<div class='info_jaune'>
			<p>⚠️ ATTENTION, vous avez actuellement <span style='color:red;font-size:150%;'>0 crédits</span> et vous ne pouvez donc pas bénéficier des avantages réservés aux comptes PREMIUM tant que vous n'avez pas chargé des unités sur votre compte.</p>
			<p>Merci <?php if ($module_de_paiement == TRUE) {?>de recharger ci-dessous votre compte ou <?php } ?>de <a href='contact.html'>nous contacter</a> au plus vite pour activer/renouveler vos avantages partenaires PREMIUM, ou de vous <a href='gestion/logout.php'>déconnecter</a> pour proposer votre site en tant qu'invité sans tous les avantages associés au compte PREMIUM.</p>
		</div>
		
				<?php $myaccount = $_SESSION['mail_user'];$mywebsites = rcp_pro_websites($myaccount); if (!empty($mywebsites)) { ?>
					<p style="text-align:center;font-size:16px;margin-bottom:50px;"><a href="parametrer.html" style="background:blue;color:white;padding:10px;border-radius:3px;">⚙️ Booster ou éditer mes sites déjà enregistrés</a></p>
				<?php } ?>
				
				<?php if ($module_de_paiement == TRUE) { include 'payp.php'; } ?>
		
		<?php } else { ?>
	
	<h2>📋 Règles de soumission</h2>
	
	<div id="rulesad">
	<ul>
		<li>Vous devez fournir une <u>description unique</u> d'au moins <span style="color:red;font-weight:bold;"><?php echo $nb_cara_description; ?> caractères OBLIGATOIREMENT !</span></li>
		<li>Votre site ne doit pas être en cours de construction.</li>
		<li>Votre site doit comporter les mentions légales obligatoires.</li>
		<?php if ($fastpass != "yes"  && $payant_only_end_of_tunel){ ?>
			<li>Tarif communiqué Step 2 en fonction de la catégorie choisie.</li>
		<?php } ?>
		<li>* désigne les champs à remplir obligatoirement.</li>
	</ul>
	</div>
	
	<h2>✨ Proposer mon site</h2>

<div id="j_join">
<form method="POST" action="ajouter2.html" name="Myform">
<style>
span.conseil {display:none;}
#popmail:focus + span.conseil.popmail,
#poptitre:focus + span.conseil.poptitre,
#popancre:focus + span.conseil.popancre,
#popurl:focus + span.conseil.popurl,
#popurlback:focus + span.conseil.popurlback,
#poprss:focus + span.conseil.poprss
{ display:block;}

</style>
	<fieldset id="ajouter2">
		<?php if ($fastpass != "yes") { ?>
		<p>
			<input id="popmail" type="mail" name="mail_auteur" class="soumettre_input" value="<?php if (!empty($_SESSION['pro_mail_auteur'])) {echo $_SESSION['pro_mail_auteur'];} ?>" placeholder="Votre adresse mail*" required="required" autocomplete="off" />
			<span class="conseil popmail">Uniquement utilisée pour vous avertir de l'acceptation ou non de votre site dans notre annuaire.</span>
		</p>
		<?php } else { ?>
			<input id="popmail" type="mail" name="mail_auteur" style="display:none;" value="<?php echo $_SESSION['mail_user']; ?>" />
		<?php } ?>
		
		<p>
			<input id="poptitre" type="text" name="titre" class="soumettre_input" value="<?php if (!empty($_SESSION['pro_titre'])) {echo $_SESSION['pro_titre'];} ?>" placeholder="Nom du site*"  required="required" autocomplete="off" />
			<span class="conseil poptitre">Sera utilisé en H1 et &lt;title> de votre fiche site</span>
		</p>
		
		
		<p>
			<input id="popurl" type="url" name="url" class="soumettre_input" value="<?php if (!empty($_SESSION['pro_url'])) {echo $_SESSION['pro_url'];} ?>" placeholder="URL de votre site*"  required="required" autocomplete="off" />
			<span class="conseil popurl">Saisissez l'adresse de votre site web (ex: https://monsite.com). Pensez à bien mettre http(s):// devant votre URL.</span>
		</p>
			
		<p>
			<input id="popancre" type="text" name="ancre" class="soumettre_input" value="<?php if (!empty($_SESSION['ancre'])) {echo $_SESSION['ancre'];} ?>" placeholder="Ancre du lien" autocomplete="off" />
			<span class="conseil popancre">Sera le mot clé cliquable qui pointera vers votre site. Laissez vide pour utiliser le titre du site comme ancre</span>
		</p>
		
		<p>
			<input id="poprss" type="url" name="url_rss" class="soumettre_input" value="<?php if (!empty($_SESSION['pro_url_rss'])) {echo $_SESSION['pro_url_rss'];} ?>" placeholder="URL de votre flux RSS" autocomplete="off" />
			<span class="conseil poprss">Permettra d'afficher vos 5 derniers articles sur votre fiche site</span>
		</p>
			
		<h3>🍝 Quelle est sa thématique ?</h3>
		
		<p>		
					<select name="sect" class="soumettre_input">
						<?php 
								$cat = rcp_cat("", "ORDER BY titre ASC");
								
									foreach($cat as $c){
									
										$sect = rcp_sect("", $c['id_cat'], "ORDER BY titre ASC");
										
											foreach($sect as $se){

										?>
							
											<option value="<?php echo $se['id_sect']; ?>"><?php echo stripslashes($c['titre']); ?> >> <?php echo stripslashes($se['titre']); ?></option>
							
											<?php }
									} ?>
					</select>
		</p>

		<h3>✍️ Présentation</h3>
		
		<p>
			<textarea id="mytextarea" class="soumettre_textarea" name="description" placeholder="Description *UNIQUE* de votre site*" required="required" minlength="<?php echo $nb_cara_description; ?>" data-minlength="<?php echo $nb_cara_description; ?>"><?php if (!empty($_SESSION['pro_description'])) {echo $_SESSION['pro_description'];} ?></textarea>
		</p>
		
		<p><span id="descriptionError" class="counter">(<?php if ($fastpass != "yes"){ ?> <span>0</span> / <?php } else {?>⚠️<?php } echo $nb_cara_description; ?> Caractères Minimum)</span></p>
		
		<?php if ($fastpass != "yes"){ ?>
			<p><small><i>⚠️ Inutile d'ajouter des balises HTML, elles seront supprimées automatiquement | Copié/Collé INTERDIT</i></small></p>
		<?php } else { ?>
			<p>✅ Balises HTML autorisées : P - H2/H3/H4/H5 - Strong - Em - ul/ol/li - Blockquote<br />✅ Import depuis Word avec nettoyage du code HTML activé (à vérifier)<br />⚠️ La balise H1> est interdite car réservée au titre de votre fiche.<br />⚠️ Les balises IMG et A sont également interdites.<br /> ⚠️ Les attributs seront automatiquement supprimés (style="" / class="" / id="" / src="" ...)</p>
		<?php } ?>
		
		<?php if ($fastpass != "yes"){ ?>
		<h3>🔗 Lien retour</h3>
		
		<table id="tab4backlink">
			<thead>
				<tr>
					<th>👀 Prévisualisation</th>
					<th>⬇️ Code HTML à copier/coller sur votre site</th>
				</tr>
			</thead>
		
			<?php // Permet de varier les backlinks pour éviter de se prendre un pingoin de Google .. Les BL demandés seront affichés aléatoirement. PS : Vous pouvez ajouter autant de variante que vous voulez...
			 $num = Rand (1,3); 
			 switch ($num)
			 {
			 case 1:
			 $backlink = "<a href='$url_annuaire'>$titre_annuaire</a>";
			 break;
			 case 2:
			$backlink = "<a href='$url_annuaire'>Annuaire $titre_annuaire</a>";
			 break;
			 case 3:
			$backlink = "<a href='$url_annuaire'>Membre de $titre_annuaire</a>";
			 }
			 ?> 
			 
			<tbody>
				<tr>
					<td>
						<?php echo $backlink; ?>
					</td>
					<td>
						<textarea readonly="readonly" onclick="this.select()" class="backlinkcode"><?php echo $backlink; ?></textarea>
					</td>
				</tr>
			</tbody>
		</table>
		
		<p>
			<input id="popurlback" type="url" name="url_retour" class="soumettre_input" value="<?php if (!empty($_SESSION['pro_url_retour'])) {echo $_SESSION['pro_url_retour'];} ?>" <?php if($forcebacklink == "oui") {echo "placeholder='URL du lien retour*' required='required'";} else {echo "placeholder='URL du lien retour'";}?> autocomplete="off" />
			<span class="conseil popurlback" <?php if($forcebacklink == "oui") {echo "style='display:block;'";} ?>>Indiquez ci-dessus l'url de la page sur laquelle vous avez placé le lien retour.</span>
		</p>
		<?php } ?>
		
		<?php if ($fastpass != "yes") { ?>
			
			<h3>🤖 Répondez à la question de sécurité :</h3>
			
			<p>❓ <?php echo $question_q; ?> ❓</p>
			
			<p>🤔 Votre réponse :</p>
			<p><input id="security_code" name="security_code" type="text" class="soumettre_input" required="required" autocomplete="off" /></p>
		
			<h3>Politique de Confidentialité</h3>
		
			<p><input type="checkbox" id="rpgd" name="rpgd" value="Consentement OK" required="required" /> <label for="rpgd">Ce site respecte le RGPD. Ceci étant, en soumettant ce formulaire, j'accepte que les informations saisies soient stockées dans une base de données afin de pouvoir constituer la fiche liée au site que vous souhaitez enregistrer. En cas de refus de votre site, la soumission sera intégralement supprimée. En cas d'acceptation, vous pouvez à tout moment <a href='contact.html'>nous contacter</a> afin de supprimer ou rectifier les informations saisies.</label></p>
		
		
		<?php } ?>
		
		
		<h3>✅ Validation de votre soumission</h3>
		<p><input value="Proposer mon site" type="submit"/></p>
	
	</fieldset>
</form>

</div>

<?php } // Fin de la condition liée aux crédits 

} // Fin de la condition du au compte payant 
		
?>

</div>

<?php if (!isset($_SESSION['loggedin'])){ ?>
<style>
.counter span{color:red;font-weight:bold;}
.counter span.warning{color:green}
</style>

<script>
function limitingData(oEvent) {
  if(isNaN(this.dataset.minlength) == false){ 
    let oDiv =  document.getElementById(this.name+"Error");
    if(oDiv){
      let oCnt =  oDiv.children[0], 
          iLongueur = this.value.length,
          iLimit = parseInt(this.dataset.minlength);  
      if(iLimit - iLongueur < 0) {
        oCnt.classList.add("warning");
        //A vous d'adapter le message 
        oCnt.textContent = iLimit - iLongueur ;
      }else{
        oCnt.classList.remove("warning");
        //A vous d'adapter le message 
        oCnt.textContent = iLongueur ;
      }
    }//if
  }//if
}//fct


document.addEventListener('DOMContentLoaded',function(){
  let aTextarea = document.getElementsByTagName('textarea');
  for(let oTextarea of aTextarea){
      //Sans limite bloquante
      oTextarea.addEventListener('input',limitingData); 
  }
});
</script>

<?php } else { ?>

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
             allowedTags: ['h2', 'h3', 'h4', 'h5', 'p', 'br', 'strong', 'em', 'ul', 'li']
        }
    }	
});</script>
<style>.trumbowyg-box .trumbowyg-editor{font-family:verdana!important;}.trumbowyg-button-pane button{width:30px;}.trumbowyg-counter{background: #000; padding:9px 5px;font-size: 13px;line-height: 18px;text-transform:uppercase;color:white;}.chars-counter{margin-left:6px;}</style>
<?php /* Editeur WYSIWYG */ ?>	

<?php } ?>

<?php

include 'footer.php'; 

?>