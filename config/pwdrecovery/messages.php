<?php 
if (!empty($errors)) {
	if (count($errors) > 0) { ?>
  <div class="msg">
  	<?php foreach ($errors as $error) : ?>
  	  <span><?php echo $error ?></span>
  	<?php endforeach ?>
  </div>
<?php 
	}
} 
?>