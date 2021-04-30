<?php
// Module pour empecher le include d'être appelé directement
if (empty($variable_temoin))
{
exit("Quelque-chose me dit que vous n'avez rien à faire ici ?!");
} 
// Module pour empecher le include d'être appelé directement

$light_token = bin2hex(random_bytes(30));
$_SESSION["lightkey"] = bin2hex(random_bytes(2));
setcookie("PaypalThx", $light_token, time()+3600, "/");
$mail_user_session_pay = $_SESSION['mail_user'];
?>

<div class="info_jaune">
		<p>Validation Express = 19.98 EUR TTC (<em>16.65€HT</em>) ⤵️</p>
</div>

<div id="smart-button-container">
		  <div style="text-align: center;">
			<div id="paypal-button-container"></div>
		  </div>
		</div>
	  <script src="https://www.paypal.com/sdk/js?client-id=<?php echo $paypal_id; ?>&currency=EUR" data-sdk-integration-source="button-factory"></script>
	  <script>
		function initPayPalButton() {
		  paypal.Buttons({
			style: {
			  shape: 'pill',
			  color: 'gold',
			  layout: 'vertical',
			  label: 'checkout',
			  
			},

			createOrder: function(data, actions) {
			  return actions.order.create({
				purchase_units: [{"description":"Validation Express <?php echo $titre_annuaire; ?> | Ref. <?php echo $id; echo $refpaypal; ?>","amount":{"currency_code":"EUR","value":19.98,"breakdown":{"item_total":{"currency_code":"EUR","value":16.65},"shipping":{"currency_code":"EUR","value":0},"tax_total":{"currency_code":"EUR","value":3.33}}}}]
			  });
			},

			onApprove: function(data, actions) {
			  return actions.order.capture().then(function(details) {
					window.location.href = 'merci.html?pt=2&k=<?php echo $light_token; ?>&id=<?php echo $id; echo $refpaypal; ?>';
			  });
			},

			onError: function(err) {
			  console.log(err);
			}
		  }).render('#paypal-button-container');
		}
		initPayPalButton();
	  </script>