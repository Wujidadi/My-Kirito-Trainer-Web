<?php

require_once 'storage/token.php';

if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW']) || $_SERVER['PHP_AUTH_USER'] !== HTTP_USERNAME || $_SERVER['PHP_AUTH_PW'] !== HTTP_PASSWORD)
{
    header('www-authenticate: Basic realm="Wujidadi\'s MyKirito Trainer"');
    header($_SERVER['SERVER_PROTOCOL'] . ' 401 Unauthorized');
    exit;
}
else
{
    $file = '/home/wujidadi/MyKiritoCommands/configs/MovementByPlayer.json';
    $json = file_get_contents($file);
    $page = <<<HTML
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>自動行動與挑戰設定</title>
        <style>
            * {
                box-sizing: border-box;
            }
            body {
                margin: 0;
            }
            div#conf {
                margin: 8px auto auto auto;
                width: calc(100vw - 16px);
                height: 80vh;
            }
            textarea {
                width: 100%;
                height: 100%;
                resize: none;
            }
            div#button-area {
                height: 36px;
                padding-top: 5px;
            }
            button {
                width: 100px;
                height: 25px;
            }
        </style>
    </head>
    <body>
        <div id="conf">
            <textarea id="jsonconf">{$json}</textarea>
            <div id="button-area">
                <button id="submit">Submit</button>
                <button id="back">Back</button>
            </div>
        </div>
        <script>
            const txtConfig = document.querySelector('#jsonconf');
            const btnSubmit = document.querySelector('#submit');
            const btnBack = document.querySelector('#back');

            btnSubmit.addEventListener('click', function() {
                if (confirm('確定覆寫設定？')) {
                    const jsonConfig = txtConfig.value;
                    fetch('api/set-config', {
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
