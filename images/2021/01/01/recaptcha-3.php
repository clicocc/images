<?php
$blockPageAccess = 0;
$blockFormAccess = 1;
$checked = 0;
 
// The function of sending a request to the Google server reCAPTCHA
function sendRequestToCaptchaServer($captcha) {
    global $checked;
 
    // Here is the secret key
    $secretKey = "HERE A SECRET KEY";
    // We form a request and send the received token to the verification server
    $url = 'https://www.google.com/recaptcha/api/siteverify';
    $data = array('secret' => $secretKey, 'response' => $captcha);
 
    $options = array(
        'http' => array(
            'header' => "Content-type: application/x-www-form-urlencoded\r\n",
            'method' => 'POST',
            'content' => http_build_query($data)
        )
    );
    $context = stream_context_create($options);
    $response = file_get_contents($url, false, $context);
    $responseKeys = json_decode($response, true);
    // From the server we get an answer about the scores - from 0 to 1.
    // 1 means it’s definitely a person, and 0 means it’s a bot
    // You can set any threshold for “passing”. I use 0.5
    if ($responseKeys["success"] AND $responseKeys["score"] > 0.5 AND $responseKeys["action"] == 'homepage') {
        $checked = 1;
        // If this is a person, then we simply do nothing.
    } else {
        // And if this is a bot, then we terminate the work. Before the quit, you can show the bot some message.
        exit;
    }
}
 
// Initialize the variable
$captcha = '';
// Check if there is a token and assign its value to the variable.
if (isset($_GET["token"])) {
    $captcha = filter_input(INPUT_GET, 'token', FILTER_SANITIZE_STRING);
} elseif (isset($_POST["token"])) {
    $captcha = filter_input(INPUT_POST, 'token', FILTER_SANITIZE_STRING);
}
 
// This section only works if the blocking of bots to pages is enabled.
if ($blockPageAccess) {
    // Check if the value is empty
    if (!$captcha) {
        // If the token is missing, then you need to show the token generating page.
        // The initial request may have GET parameters - we collect them to pass to the final page
        $get = '';
        foreach ($_GET as $key => $value) {
            $get = $get . "&$key=$value";
        }
        // Print the token generating code
        echo '
<!DOCTYPE html>
 
<html>
    <head>
        <title>Are you a human being?</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://www.google.com/recaptcha/api.js?render=HERE SITE KEY"></script>
    </head>
    <body>
        <script>
            grecaptcha.ready(function () {
                grecaptcha.execute(\'HERE SITE KEY\', {action: \'homepage\'}).then(function (token) {
                    //alert(token)
                    window.location.replace("?token=" + token + "' . $get . '");
                });
            });
        </script>
    </body>
</html>
';
        // No more business - quit. Now the user will come again, but with the token
        exit;
    }
    // If a use has the token, then initiate a request to the server.
    sendRequestToCaptchaServer($captcha);
}
 
// This section is triggered if you need to validate requests from forms.
// And if bot checking is enabled when accessing pages, it means that the token has already
// been used for verification and it makes no more sense to do it again.
// In any case, it is impossible to use the same token a second time.
if ($blockFormAccess AND ! $checked) {
    // If the $ _POST array contains more than zero values, then sending from the form takes place.
    // Otherwise, we have nothing to do.
    if (count($_POST) > 0) {
        if (!$captcha) {
            // If the captcha is empty, then we terminate the work.
            die('Your request is not accepted.');
        } else {
            // If a use has the token, then initiate a request to the server.
            sendRequestToCaptchaServer($captcha);
        }
    }
}