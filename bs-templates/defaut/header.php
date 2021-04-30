<!doctype html>
<?php if (empty($noamp)) {$amp = $_GET["amp"]; if (isset($amp)) {$amp=true;} else {$amp=NULL;} }?>
<html <?php if ($amp == true) {echo "⚡ ";} ?>lang="fr">
<head>
<meta charset="utf-8">

<?php
// Module pour empecher le include d'être appelé directement
if (empty($variable_temoin))
{
exit("Quelque-chose me dit que vous n'avez rien à faire ici ?!");
} 
// Module pour empecher le include d'être appelé directement
?>

<?php echo head(); ?>

<link rel="shortcut icon" href="<?php echo $url_annuaire; ?>favicon.png" />

<meta name="viewport" content="width=device-width, initial-scale=1">

<meta property="og:image" content="<?php echo $url_annuaire; ?>images/facebook_og.png">
<meta property="og:locale" content="fr_FR"/>

<link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@700&display=swap" rel="stylesheet" type='text/css'>
<?php if ($amp == true) { ?>

	<script async src="https://cdn.ampproject.org/v0.js"></script>
	<link rel="preconnect dns-prefetch" href="https://fonts.gstatic.com/" crossorigin>
	<?php if (!empty($google_analytics)) { ?><script async custom-element="amp-analytics" src="https://cdn.ampproject.org/v0/amp-analytics-0.1.js"></script><?php } ?>
	<style amp-boilerplate>body{-webkit-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-moz-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-ms-animation:-amp-start 8s steps(1,end) 0s 1 normal both;animation:-amp-start 8s steps(1,end) 0s 1 normal both}@-webkit-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-moz-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-ms-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-o-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}</style><noscript><style amp-boilerplate>body{-webkit-animation:none;-moz-animation:none;-ms-animation:none;animation:none}</style></noscript>
	
	<?php
	function minimizeCSSsimple($css){
	$css = preg_replace('/\/\*((?!\*\/).)*\*\//', '', $css); // negative look ahead
	$css = preg_replace('/\s{2,}/', ' ', $css);
	$css = preg_replace('/\s*([:;{}])\s*/', '$1', $css);
	$css = preg_replace('/;}/', '}', $css);
	$css = str_replace('!important', '', $css);
	return $css;
	}
	?>
	<style amp-custom>
    <?php 
	$amp_url_css = BATBASE.'/bs-templates/defaut/style.css';
	$smart_amp_css = file_get_contents($amp_url_css); 
	echo minimizeCSSsimple($smart_amp_css, FILE_USE_INCLUDE_PATH); 
	?>
	
    </style>
	
	<?php } else { ?>
	
		<base href="<?php echo $url_annuaire; ?>" />
		<link rel="stylesheet" href="<?php echo $url_annuaire; ?>bs-templates/defaut/style.css" />
		
		<?php if (!empty($google_analytics)) { ?>
			<!-- Global site tag (gtag.js) - Google Analytics -->
			<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $google_analytics; ?>"></script>
			<script>
			  window.dataLayer = window.dataLayer || [];
			  function gtag(){dataLayer.push(arguments);}
			  gtag('js', new Date());

			  gtag('config', '<?php echo $google_analytics; ?>');
			</script>
		<?php } ?>
		
	<?php } ?>
	

</head>
		 
<body>

<div id="header">
<?php 
// POSSIBILITE DE METTRE UN TITRE EN H1 SUR LA HOME QUI BASCULE EN SPAN SUR LES AUTRES PAGES, en dé-commentant ce block PHP. 
// /!\ Pensez alors à virer le H1 sur l'édito de la homepage pour n'avoir qu'une seule balise H1 par page !
/* 

<div class="toptoptop">	
	if ($home == "yes") {?>
		<h1><a href="<?php echo $url_annuaire; ?>"><?php echo $titre_annuaire; ?></a></h1>
	<?php } else { ?>
		<span class="likeh"><a href="<?php echo $url_annuaire; ?>"><?php echo $titre_annuaire; ?></a></span>
	<?php } 
</div>

*/ ?>
<div class="lelogo"><a href="<?php echo $url_annuaire; ?>"><<?php echo $imgtag; ?> width="725" height="200" src="<?php echo $url_annuaire; ?>bs-templates/defaut/images/logo.png" alt="logo" ><?php echo $imgtag_close; ?></a></div>

</div>

<div class="center" >
	<div class="content width">
		<div id="contenu">