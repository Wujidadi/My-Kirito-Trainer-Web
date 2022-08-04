<?php

require_once 'configs/env.php';
require_once 'storage/token.php';

if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW']) || $_SERVER['PHP_AUTH_USER'] !== HTTP_USERNAME || $_SERVER['PHP_AUTH_PW'] !== HTTP_PASSWORD)
{
    $basicRealm = BASIC_REALM;
    header("www-authenticate: Basic realm={$basicRealm}");
    header($_SERVER['SERVER_PROTOCOL'] . ' 401 Unauthorized');
    exit;
}
else
{
    require_once 'lib/helpers.php';

    $title = '玩家 Token 設定';

    $style = require_once 'storage/textarea-style.php';

    $home = HOME;
    $file = "{$home}/workspaces/MyKirito/storage/configs/Players.json";
    $json = file_get_contents($file);

    $buttonArea = submitAndBackButtonArea(4, 2);

    $page = <<<HTML
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{$title}</title>
        <style>{$style}</style>
    </head>
    <body>
        <div id="conf">
            <textarea id="jsonconf">{$json}</textarea>
            {$buttonArea}
        </div>
        <script>
            const txtConfig = document.querySelector('#jsonconf');
            const btnSubmit = document.querySelector('#submit');
            const btnBack = document.querySelector('#back');

            btnSubmit.addEventListener('click', function() {
                if (confirm('確定覆寫設定？')) {
                    const jsonConfig = txtConfig.value;
                    fetch('api/set-token', {
                        method: 'POST',
                        headers: {
                            'content-type': 'application/json'
                        },
                        body: jsonConfig
                    })
                    .then(response => response.text())
                    .then(response => {
                        alert(response);
                        if (response === '設定已覆寫！') {
                            location.href = '/';
                        }
                    })
                    .catch(error => {
                        alert(error);
                    });
                }
            });

            btnBack.addEventListener('click', function() {
                if (confirm('確定返回？')) {
                    location.href = '/';
                }
            });
        </script>
    </body>
    </html>
    HTML;

    echo $page;
}
