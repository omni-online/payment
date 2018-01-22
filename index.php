<?php
  if ($_GET) {
    if (array_key_exists('preview', $_GET)) {
      define("STRIPE_PK", "pk_test_TEST");
      define("STRIPE_SK", "sk_test_TEST");
    }

    if (array_key_exists('cid', $_GET)) {
      $cid = $_GET['cid'];

      /**
       * maybe check to make sure it is actually a real person,
       * otherwise redirect to the url without any query params.
       */
    }
  }

  if (!defined('STRIPE_PK')) {
    define("STRIPE_PK", "pk_live_LIVE");
    define("STRIPE_SK", "sk_live_LIVE");
  }
?>


<html>
  <head>
    <title>Omni Online LLC - Customer Creation Portal</title>
    <!-- <link href="https://fonts.googleapis.com/css?family=Lato:100,100i,300,300i,400,400i,700,700i,900,900i" rel="stylesheet"> -->
    <link href="https://fonts.googleapis.com/css?family=Lato:400,700" rel="stylesheet">
    <!-- <script defer src="/js/fontawesome/fontawesome-all.min.js"></script> -->
    <style>
      html, body {
        padding: 0;
        margin: 0;
        color: #fff;
        background: #fefefe;
        background: #090013;
        font-family: 'Lato', sans-serif;
        font-size: 16px;
        line-height: 1.5;
        -webkit-font-smoothing: antialiased;
      }
      .hide {
        display: none;
      }
      .bg {
        background-image: url('/img/space.jpg');
        background-size: cover;
        opacity: .2;
        pointer-events: none;
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 0;
      }

      header {
        position: relative;
        z-index: 10;
      }
      .logo--img {
        margin: 20px 0 80px 20px;
      }

      main {
        max-width: 400px;
        width: 80vw;
        margin: 0 auto;
        box-sizing: border-box;
        position: relative;
        z-index: 10;
      }

      h1 {
        font-size: 2em;
        font-weight: 400;
      }
      @media screen and (min-width: 550px) {
        h1 {
          font-size: 2.5em;
        }
      }

      p {
        color: hsla(0,100%,100%,.6);
      }
      p b {
        color: hsla(0,100%,100%,1);
        margin: 0 2px;
      }
      .stripe-button-el {
        margin-top: 20px;
      }
      button.stripe-button-el span {
        padding: 4px 18px;
      }

      .alert {
        padding: 1em;
      }

      .alert p {
        margin: 0;
      }

      .success {
        border: 1px solid #97d297;
        border-radius: 10px;
        margin: 40px 0 20px;
      }
      .success, .success p {
        color: #97d297;
      }

      .error {
        border: 1px solid #de2951;
        border-radius: 10px;
        margin: 40px 0 20px;
      }
      .error, .error b, .error p {
        color: #de2951;
      }

      .processing {

      }
    </style>
  </head>
  <body>
    <div class="bg"></div>
    <header>
      <img class="logo--img" src="/img/omni-online-logo.svg" width="200">
    </header>
    <main>
      <h1>Pillar Hosting and Maintenance</h1>

      <p>Enter billing information for your church's Pillar website hosting and maintenance. The cost is <b>$19.44/month</b>, and will begin on <b>February 1<sup>st</sup></b>.</p>

      <?php if ($_POST) {

        $email = $_POST["stripeEmail"];
        $token = $_POST["stripeToken"];

  /* How to create a customer in Stripe:
  curl https://api.stripe.com/v1/customers \
   -u sk_test_DaJl1VIYuVXeZAwdJ9wlysp5: \
   -d email="paying.user@example.com" \
   -d source=tok_jhialK72293wzDSszByxYWd1
  */

  /* Update existing credit information
  curl https://api.stripe.com/v1/customers/cus_uAPbCxuZHVxFFi \
     -u sk_test_DaJl1VIYuVXeZAwdJ9wlysp5: \
     -d source=tok_SeVOXMg5Td6bGXTFn3yLhFAD
  */


        $url = ($cid ? 'https://api.stripe.com/v1/customers/' . $cid : 'https://api.stripe.com/v1/customers');

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(array(
          'email' => $email,
          'source' => $token
        )));

        // Authentication:
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_USERPWD, STRIPE_SK . ":");

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $result = json_decode(curl_exec($curl), true);

        curl_close($curl);
        if (!$result || !array_key_exists('id', $result) || array_key_exists('error', $result)) {
          $errorMsg = "Something went wrong. Please constact us as to what happened.";
          if (array_key_exists('error', $result) && array_key_exists('message', $result['error'])) {
            $errorMsg = $result['error']['message'];
          } ?>
          <div class="alert error">
            <p><b>Uh oh!</b></p>
            <?php echo $errorMsg ?>
          </div>
        <?php } else { // end error check ?>
          <div class="alert success">
            <p>Your billing information was submitted successfully!</p>
            <!-- Customer "<?php echo $result['id'] ?>" has been successfully <?php echo ($cid ? "updated" : "created") ?>. You can now close this window. -->
          </div>
        <?php } ?>
      <?php } ?>

      <form method="POST">
      <script
        src="https://checkout.stripe.com/checkout.js" class="stripe-button"
        data-zip-code="true"
        data-billing-address="true"
        data-key="<?php echo STRIPE_PK ?>"
        data-name="Omni Online LLC"
        data-label="Enter billing info"
        data-description="Create a payment option"
        data-allow-remember-me=false
        data-locale="auto">
      </script>
      </form>
      <i class="hide processing fas fa-spinner fa-pulse"></i>
    </main>
  </body>
</html>
