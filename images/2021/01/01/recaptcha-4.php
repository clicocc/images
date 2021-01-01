<?php
if ($blockFormAccess) {
    echo '
        <script src="https://www.google.com/recaptcha/api.js?render=HERE SITE KEY"></script>
        <script>
            document.forms[0].addEventListener(\'submit\', function (evt) {
                evt.preventDefault();
                grecaptcha.ready(function () {
                    grecaptcha.execute(\'HERE SITE KEY\', {action: \'homepage\'}).then(function (token) {
                        var el = document.createElement("input");
                        el.type = "hidden";
                        el.name = "token";
                        el.value = token;
                        document.forms[0].appendChild(el);
                        document.forms[0].submit();
                    });
                });
            })
        </script>';
}
?>