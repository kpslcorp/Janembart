<?php
// Module pour empecher le include d'être appelé directement
if (empty($variable_temoin))
{
exit("Quelque-chose me dit que vous n'avez rien à faire ici ?!");
} 
// Module pour empecher le include d'être appelé directement

$site = rcp_site($_GET['id'], "", "", "", "", "");
$s = $site[0];
$lid = $s['id_site']; 

if ($s['type'] != 1 OR $s['valide'] != 1) {$fire404 = "yes";} // Si Pas de Fiche ou Pas encore Validé

if (empty($site) OR ($fire404 == "yes"))
{
header("Status: 301 Moved Permanently", false, 301);
header("Location: $url_annuaire");
exit();
} 

if ($_GET['titre'] != clean($s['titre'])) {$droitchemin = $s['info']['permanlink'];header("Status: 301 Moved Permanently", false, 301);header("Location: $droitchemin");exit();} // Si le nom ne colle pas dans l'url

include 'header.php';

?>
	
<div id="page_content">
	
		
		
		<div id="breadcrumb">
			
			<p id="fil_ariane" itemscope itemtype="https://schema.org/BreadcrumbList">
										<span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
											<a href="<?php echo $url_annuaire; ?>" rel="home" itemtype="https://schema.org/Thing" itemprop="item"><span itemprop="name"><?php echo $titre_annuaire; ?></span></a><meta itemprop="position" content="1" />
										</span> &raquo;
										<span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
											<a href="<?php echo $s['info']['cat']['permanlink']; ?>" title="<?php echo stripslashes($s['info']['cat']['titre']); ?>" itemtype="https://schema.org/Thing" itemprop="item"><span itemprop="name"><?php echo stripslashes($s['info']['cat']['titre']); ?></span></a><meta itemprop="position" content="2" />
										</span> &raquo;
										<span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
											<a href="<?php echo $s['info']['section']['permanlink']; ?>" title="<?php echo stripslashes($s['info']['section']['titre']); ?>" itemtype="https://schema.org/Thing" itemprop="item"><span itemprop="name"><?php echo stripslashes($s['info']['section']['titre']); ?></span></a><meta itemprop="position" content="3" />
										</span>	&raquo;
										<span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
											<a href="<?php echo $s['info']['permanlink']; ?>" title="<?php echo stripslashes($s['titre']); ?>" itemtype="https://schema.org/Thing" itemprop="item"><span itemprop="name"><?php echo stripslashes($s['titre']); ?></span></a><meta itemprop="position" content="4" />
										</span>
			</p>
		
		</div>
		
		<?php // MODULE D'ADMIN RAPIDE QUI N'APPARAIT QUE POUR ET QUAND ADMIN LOGGUE

		if (isset($_SESSION['loggedin']) AND ($_SESSION['statut'] == "kaioh")){ 
	
		$lagestion = $url_annuaire."gestion/?act=1&id=".$lid;	?>
	
		<p class="fastadmin"><a href="<?php echo $lagestion; ?>" rel="nofollow">🛡️ Administrer</a></p>

		<?php 
		}
		// FIN DU MODULE
		 ?>
			
		<h1 class="titre_site"><?php echo stripslashes($s['titre']); ?></h1>

		<div class="description_site">
		
			<div class="j_thumbs">
				<<?php echo $imgtag_vignette; ?> src="https://www.robothumb.com/src/?url=<?php echo $s['url']; ?>&size=640x480" alt="<?php echo stripslashes($s['titre']); ?>"><?php echo $imgtag_close; ?>
			</div>

			<div class="j_site_description">
			
			<?php echo htmlspecialchars_decode(nl2br(stripslashes($s['description']))); ?>
			
			<?php if (!empty($s['ancre'])) {$janembart_anchor = stripslashes($s['ancre']);} else { $janembart_anchor = stripslashes($s['titre']); } ?>
			
			<?php if ($s['url'] != "") { ?>
				<p class="j_go2site">🌍 Ce contenu vous a plu ?! Retrouvez plus d'infos sur « <strong><a href="<?php echo $s['url']; ?>" title="<?php echo stripslashes($s['titre']); ?>" target="_blank"><?php echo $janembart_anchor; ?></a></strong>  » référencé dans la catégorie « <em><?php echo stripslashes($s['info']['section']['titre']); ?></em> » de notre répertoire.</p>
			<?php } ?>
			</div>
		</div>
		
 <div style='clear:both;'></div>
		
<?php if (!empty($s['url_rss'])) {
	
	$curl = curl_init(); // On lance curl
	
	$url_rss = $s['url_rss']; // On récupère l'url du RSS
	// Module pour corriger le bug sur les flux Wordpress
	$slashornot = substr("$url_rss", -4); // On récupère les 4 derniers caractères de l'URL
	if ($slashornot == "feed") {$url_rss = "$url_rss/";} // Si l'url termine par feed mais sans le / on le rajoute.


	curl_setopt_array($curl, Array(
		CURLOPT_URL            => $url_rss,
		CURLOPT_HEADER         => false, 
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_USERAGENT      => 'spider',
		CURLOPT_TIMEOUT        => 120,
		CURLOPT_CONNECTTIMEOUT => 30,
		CURLOPT_RETURNTRANSFER => TRUE,
		CURLOPT_ENCODING       => 'UTF-8',
		CURLOPT_MAXREDIRS      => 10,
	));

	$data = curl_exec($curl);

	curl_close($curl);

	$xml = simplexml_load_string($data, 'SimpleXMLElement', LIBXML_NOCDATA);

	if ($xml!="") {
		echo "<h2 class='titre_colonne'>📰 Derniers billets du média :</h2><ul class='fluxrss'>";
					for($i = 0; $i < 5; $i++){
						$title = $xml->channel->item[$i]->title;
						$link = $xml->channel->item[$i]->link;
						if (empty($title)) { 
							break; 
						}
						else {
							echo "<li><a target='_blank' href='$link'>$title</a></li>";
						}
					}
		echo "</ul><div style='clear:both;'></div>
   ";
	}
} ?>

<?php 
$id_site = $s['id_site'];
$sitecomplementaires = rcp_site("", $s['sect'], "AND id_site NOT LIKE '$id_site' ORDER BY RAND()", 1, 1, "LIMIT 0, 3");
if($sitecomplementaires != "") {
?> 

<h2 class='titre_colonne'>💖 Vous allez aimer ces sites :</h2>
   <div class="site_relatif">
		<ul>   
			<?php
				foreach($sitecomplementaires as $s){
					if($id_site!=$s['id_site']) {
						$titrecomp = stripslashes($s['titre']);
						$titrecomp = ucfirst($titrecomp);
						?>
						<li><a href="<?php echo $s['info']['permanlink']; ?>" title="<?php echo $titrecomp; ?>"><?php echo $titrecomp; ?></a></li>
					<?php } ?>

				<?php } ?>
		</ul>
	</div>
	
<?php }  ?> 

<p class="upwork">⚠️ Ce site ne fonctionne plus ou est spammy ? <a href="contact.html?pb=<?php echo $lid; ?>" rel="nofollow">Aidez-nous à faire le ménage en nous le signalant</a>... 🤫 Vous allez pouvoir gagner des places au classement !</p>

</div>

<?php				
include 'footer.php';
?>