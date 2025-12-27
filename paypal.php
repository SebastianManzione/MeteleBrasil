<?php
// Cargar PayPal Client ID desde BD
require_once(__DIR__ . '/admin/classes/conexion.php');
require_once(__DIR__ . '/admin/classes/configuracion.php');

try {
    $config = new Configuracion();
    $paypal_client_id = $config->obtener('paypal_client_id_1', 'AeV_6mpCIQUkgigJeObgPjqNJm9dtGpRbtWMZpF4z773Tw-Adj8hfrNA8WxzwM1_psRTaPXAv7akGBk9');
} catch (Exception $e) {
    // Fallback si hay error
    $paypal_client_id = 'AeV_6mpCIQUkgigJeObgPjqNJm9dtGpRbtWMZpF4z773Tw-Adj8hfrNA8WxzwM1_psRTaPXAv7akGBk9';
}
?>

  <script src="https://www.paypal.com/sdk/js?client-id=<?php echo htmlspecialchars($paypal_client_id); ?>"></script>

    <div id="paypal-button-container"></div>


  <script>
  paypal.Buttons({
    createOrder: function(data, actions) {
      // This function sets up the details of the transaction, including the amount and line item details.
      return actions.order.create({
        purchase_units: [{
          amount: {
            value: '0.01'
          }
        }]
      });
    },
    onApprove: function(data, actions) {
      // This function captures the funds from the transaction.
      return actions.order.capture().then(function(details) {
        // This function shows a transaction success message to your buyer.
        alert('Transaction completed by ' + details.payer.name.given_name);
      });
    }
  }).render('#paypal-button-container');
  //This function displays Smart Payment Buttons on your web page.
</script>