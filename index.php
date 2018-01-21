<?php
  define("STRIPE_PK", "pk_test_WHATEVER");
  define("STRIPE_SK", "sk_test_WHATEVER")
?>


<html>
  <head>
    <title>Omni Online LLC - Customer Creation Portal</title>
    <style>
      html, body {
        padding: 0;
        margin: 0;
        color: #222;
        background: #fefefe;
        font-family: Helvatica, Arial, "sans-serif";
      }

      main {
        max-width: 800px;
        width: 100%;
        padding: 1em;
        background: #fff;
        margin: 1em auto;
        box-sizing: border-box;
      }

      h1 {
        font-size: 1.5em;
      }

      .alert {
        padding: 1em;
      }

      .alert p {
        margin: 0;
        font-weight: bold;
      }

      .success {
        background: #efe;
      }

      .error {
        background: #fee;
      }
    </style>
  </head>
  <body>
    <main>
      <h1>Omni Online LLC - Customer Creation Portal</h1>
      <?php if ($_POST) {
        $email = $_POST["stripeEmail"];
        $token = $_POST["stripeToken"];

  /* How to create a customer in Stripe:
  curl https://api.stripe.com/v1/customers \
   -u sk_test_DaJl1VIYuVXeZAwdJ9wlysp5: \
   -d email="paying.user@example.com" \
   -d source=tok_jhialK72293wzDSszByxYWd1
  */
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(array(
          'email' => $email,
          'source' => $token
        )));

        // Authentication:
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_USERPWD, STRIPE_SK . ":");

        curl_setopt($curl, CURLOPT_URL, "https://api.stripe.com/v1/customers");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $result = json_decode(curl_exec($curl), true);

        curl_close($curl);
        if (!$result || !array_key_exists('id', $result) || array_key_exists('error', $result)) {
          $errorMsg = "Something went wrong. Please constact us as to what happened.";
          if (array_key_exists('error', $result) && array_key_exists('message', $result['error'])) {
            $errorMsg = $result['error']['message'];
          } ?>
          <div class="alert error">
            <p>Uh oh!</p>
            <?php echo $errorMsg ?>
          </div>
        <?php } else { // end error check ?>
          <div class="alert success">
            <p>Congratulations!</p>
            Customer "<?php echo $result['id'] ?>" has been successfully created. You can now close this window.
          </div>
        <?php } ?>
      <?php } ?>

      <p>Use the button below to begin processing a payment. Be sure to include your contact email so we can match it with the invoice</p>

      <form method="POST">
      <script
        src="https://checkout.stripe.com/checkout.js" class="stripe-button"
        data-key="<?php echo STRIPE_PK ?>"
        data-name="Omni Online LLC"
        data-description="Create a payment option"
        data-locale="auto">
      </script>
      </form>
    </main>
  </body>
</html>
