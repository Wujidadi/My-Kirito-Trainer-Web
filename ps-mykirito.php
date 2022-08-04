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

    $title = '檢查進程';

    $style = require_once 'storage/common-style.php';

    $home = HOME;
    $output = recoverCliOutput(shell_exec("export LANG=C.UTF-8; php {$home}/workspaces/MyKiritoCommands/PsMyKirito"));
    $outputWithButtonArea = outputWithBackButtonArea($output);

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
    <body>{$outputWithButtonArea}</body>
    </html>
    HTML;

    echo $page;
}
