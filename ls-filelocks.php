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
    require_once 'lib/helpers.php';

    $output = recoverCliOutput(shell_exec('export LANG=C.UTF-8; php /home/wujidadi/MyKiritoCommands/LsFileLocks'));

    $page = <<<HTML
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>列出檔案鎖</title>
        <style>
            * {
                box-sizing: border-box;
            }
            body {
                margin: 0;
            }
            pre#command-output {
                background-color: lavender;
                color: mediumblue;
                margin: 8px;
                padding: 5px;
                border-radius: 5px;
                min-height: 25px;
                white-space: pre-wrap;
                word-break: break-all;
            }
            div#button-area {
                height: 36px;
                margin: 8px;
                padding-top: 5px;
            }
            button {
                width: 100px;
                height: 25px;
            }
        </style>
    </head>
    <body>
        <pre id="command-output">{$output}</pre>
        <div id="button-area">
            <button id="back">Back</button>
        </div>
        <script>
            const btnBack = document.querySelector('#back');
            btnBack.addEventListener('click', function() {
                location.href = '/';
            });
        </script>
    </body>
    </html>
    HTML;

    echo $page;
}
