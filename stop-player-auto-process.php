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

    $title = '停止玩家自動腳本';

    $style = require_once 'storage/common-style.php';

    $home = HOME;
    $player = $_GET['p'];
    $output = recoverCliOutput(shell_exec("export LANG=C.UTF-8; php {$home}/workspaces/MyKiritoCommands/StopPlayerAutoProcess --player={$player}"));
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
