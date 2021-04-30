</div>
<div id="colonne">
		<div class="space">
			<div class="titre_colonne">👀 Go 2 My Site</div>
			<div class="widget_colonne">
				<ul>
					<li>🏠 <a href="<?php echo $url_annuaire; ?>">Homepage du site</a></li>
					<li>🆕 <a href="<?php echo $url_annuaire; ?>ajouter.html">Ajouter un site</a></li>
					
				</ul>
			</div>
		</div>
		
		<div class="space">
			<div class="titre_colonne">⚙️ Gérer mon Annuaire</div>
			<div class="widget_colonne">
				<ul>
					
					<li>⚙️ <a href="<?php echo $url_annuaire; ?>gestion/">Accueil de l'Admin</a></li>
					<li>⌛ <a href="<?php echo $url_annuaire; ?>gestion/">Sites en attente</a></li>
					<li>👁️‍🗨️ <a href="<?php echo $url_annuaire; ?>gestion/?act=1&f=6">Tous les sites</a></li>
					<li>💠 <a href="<?php echo $url_annuaire; ?>gestion/?act=8">Gérer les catégories</a></li>
					<li>💬 <a href="<?php echo $url_annuaire; ?>gestion/?act=9">Gérer les pages</a></li>
					<li>💰️ <a href="<?php echo $url_annuaire; ?>gestion/?act=99">Gérer les comptes pro</a></li>
					<li>🔗 <a href="<?php echo $url_annuaire; ?>gestion/?act=67">Gérer les retraits de backlinks</a></li>
					<li>🐱‍👤 <a href="<?php echo $url_annuaire; ?>gestion/#spam">Blacklist Spam</a></li>
					<li>🗑️ <a href="<?php echo $url_annuaire; ?>gestion/?act=cache">Vider le cache</a></li>
					<li>❌ <a href="<?php echo $url_annuaire; ?>gestion/logout.php">Déconnexion</a></li>
					
				</ul>
			</div>
		</div>
		
		<div class="space">
			<div class="titre_colonne">🆘 Support / News</div>
			<div class="widget_colonne">
				<ul>
					<li>🌐 <a href="https://janembart.com/" target="_blank">News Janembart (Site officiel)</a></li>
					<li>🙋 <a href="https://janembart.com/faq/" target="_blank">FAQ janembart</a></li>		
				</ul>
			</div>
		</div>
		
	</div>
</div>
	
<div class="clear"></div>

</div>

 <div id="footer" style="margin-top:10px">
	<?php echo copyright(); ?>
</div>
<?php /* Editeur WYSIWYG */ ?>	
<script src="//ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="<?php echo $url_annuaire; ?>js/trumbowyg/trumbowyg.min.js"></script>
<link rel="stylesheet" href="<?php echo $url_annuaire; ?>js/trumbowyg/trumbowyg.min.css">

<?php // Plugins WYSIWYG : Attention à bien intégrer les versions MIN en JS /!\ ?>
<script src="<?php echo $url_annuaire; ?>js/trumbowyg/plugins/history/trumbowyg.history.min.js"></script>
<script src="<?php echo $url_annuaire; ?>js/trumbowyg/plugins/cleanpaste/trumbowyg.cleanpaste.min.js"></script>


<script>$('#mytextarea').trumbowyg({
svgPath: '<?php echo $url_annuaire; ?>js/trumbowyg/icons.svg',
btns: [
        ['viewHTML'],
        ['historyUndo', 'historyRedo'],
        ['formatting'],
        ['strong', 'em', 'del'],
        ['link'],
        ['insertImage'],
        ['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],
        ['unorderedList', 'orderedList'],
        ['removeformat'],
        ['fullscreen']
    ]
});</script>


<script>$('#relancebl').trumbowyg({
		svgPath: '<?php echo $url_annuaire; ?>js/trumbowyg/icons.svg',
		btns: [
				['viewHTML'],
				['historyUndo', 'historyRedo'],
				['formatting'],
				['strong', 'em', 'del'],
				['link'],
				['insertImage'],
				['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],
				['unorderedList', 'orderedList'],
				['removeformat'],
				['fullscreen']
			]
		});</script>

<style>.trumbowyg-box .trumbowyg-editor{font-family:verdana!important;}.trumbowyg-button-pane button{width:30px;}</style>
<?php /* Editeur WYSIWYG */ ?>	

</body>
</html>