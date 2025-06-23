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

<div class="titre_colonne" id="recharger">💳 Recharger des crédits</div>
					<p style="text-align:center;">Vos crédits apparaitront sur votre compte sous 48H maximum.</p>
					<div id="smart-button-container">
					  <div style="text-align: center;">
						<div style="margin-bottom: 1.25rem;">
						<select id="item-options">
							<option value="1 crédit <?php echo $titre_annuaire; ?> | ID : <?php echo $mail_user_session_pay; ?>" price="19.90">1 crédit - 19.90 EUR HT</option>
							<option value="10 crédits <?php echo $titre_annuaire; ?> | ID : <?php echo $mail_user_session_pay; ?>" price="169">10 crédits - 169.00 EUR HT (soit 16.90€ HT/unité)</option>
							<option value="25 crédits <?php echo $titre_annuaire; ?> | ID : <?php echo $mail_user_session_pay; ?>" price="399">25 crédits - 399.00 EUR HT (soit 15.96€ HT/unité)</option>
							<option value="50 crédits <?php echo $titre_annuaire; ?> | ID : <?php echo $mail_user_session_pay; ?>" price="699">50 crédits - 699.00 EUR HT (soit 13.98 € HT/unité)</option>
							<option value="100 crédits <?php echo $titre_annuaire; ?> | ID : <?php echo $mail_user_session_pay; ?>" price="990">100 crédits - 990.00 EUR HT (soit 9.90€ HT/unité)</option>
						</select>
						  <select style="visibility: hidden" id="quantitySelect"></select>
						</div>
					  <div id="paypal-button-container"></div>
					  </div>
					</div>
					<script src="https://www.paypal.com/sdk/js?client-id=<?php echo $paypal_id; ?>&currency=EUR" data-sdk-integration-source="button-factory"></script>
					<script>
					function initPayPalButton() {
						var shipping = 0;
						var itemOptions = document.querySelector("#smart-button-container #item-options");
					var quantity = parseInt();
					var quantitySelect = document.querySelector("#smart-button-container #quantitySelect");
					if (!isNaN(quantity)) {
					  quantitySelect.style.visibility = "visible";
					}
					var orderDescription = '';
					if(orderDescription === '') {
					  orderDescription = 'Item';
					}
					paypal.Buttons({
					  style: {
						shape: 'pill',
						color: 'blue',
						layout: 'vertical',
						label: 'checkout',
						
					  },
					  createOrder: function(data, actions) {
						var selectedItemDescription = itemOptions.options[itemOptions.selectedIndex].value;
						var selectedItemPrice = parseFloat(itemOptions.options[itemOptions.selectedIndex].getAttribute("price"));
						var tax = (20 === 0) ? 0 : (selectedItemPrice * (parseFloat(20)/100));
						if(quantitySelect.options.length > 0) {
						  quantity = parseInt(quantitySelect.options[quantitySelect.selectedIndex].value);
						} else {
						  quantity = 1;
						}

						tax *= quantity;
						tax = Math.round(tax * 100) / 100;
						var priceTotal = quantity * selectedItemPrice + parseFloat(shipping) + tax;
						priceTotal = Math.round(priceTotal * 100) / 100;
						var itemTotalValue = Math.round((selectedItemPrice * quantity) * 100) / 100;
						

						return actions.order.create({
						  purchase_units: [{
							description: orderDescription,
							amount: {
							  currency_code: 'EUR',
							  value: priceTotal,
							  breakdown: {
								item_total: {
								  currency_code: 'EUR',
								  value: itemTotalValue,
								},
								shipping: {
								  currency_code: 'EUR',
								  value: shipping,
								},
								tax_total: {
								  currency_code: 'EUR',
								  value: tax,
								}
							  }
							},
							items: [{
							  name: selectedItemDescription,
							  unit_amount: {
								currency_code: 'EUR',
								value: selectedItemPrice,
							  },
							  quantity: quantity
							}]
						  }]
						});
					  },
					  onApprove: function(data, actions) {
						return actions.order.capture().then(function(details) {
						 	window.location.href = 'merci.html?pt=1&k=<?php echo $light_token; ?>&id=<?php echo $mail_user_session_pay; ?>';
						});
					  },
					  onError: function(err) {
						console.log(err);
					  },
					}).render('#paypal-button-container');
				  }
				  initPayPalButton();
					</script>