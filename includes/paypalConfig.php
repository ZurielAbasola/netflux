<?php
require_once("PayPal-PHP-SDK/autoload.php");

$apiContext = new \PayPal\Rest\ApiContext(
    new \PayPal\Auth\OAuthTokenCredential(
        'AfcN7190XKPr8ir5W4dUGjVFGOrQYbbpWdbNiYhgJG8Yj3XfzLGJUy5WHnsuKtIIMc6kNt0FmQXGj0j5',     // ClientID
        'EDn4dQr0-jsjidlAtn0o_n3erCNd02GW4If4UlLdlFuuXswamvyglUHFD1D34tVYxpmGZUadPopftGKP'     // ClientSecret
    )
);
?>