<?php

require_once 'configs/env.php';
require_once 'storage/token.php';

if (!isset($_SERVER['PHP_AUTH_USER']) || $_SERVER['PHP_AUTH_USER'] !== HTTP_USERNAME ||
    !isset($_SERVER['PHP_AUTH_PW'])   || $_SERVER['PHP_AUTH_PW']   !== HTTP_PASSWORD)
{
    $basicRealm = BASIC_REALM;
    header("www-authenticate: Basic realm={$basicRealm}");
    header($_SERVER['SERVER_PROTOCOL'] . ' 401 Unauthorized');
    exit;
}
else
{
    require_once 'lib/helpers.php';

    $title = '列出玩家日誌';

    $style = require_once 'storage/common-style.php';

    $home = HOME;
    $player = $_GET['p'];
    $logType = $_GET['t'];
    $logLevel = $_GET['v'];
    $logDate = $_GET['d'];
    $logLine = $_GET['l'];
    $script = "{$home}/workspaces/MyKiritoCommands/ViewPlayerLog";
    $arguments = "--player={$player} --type={$logType} --level={$logLevel} --date={$logDate} --line={$logLine}";
    $output = htmlentities(recoverCliOutput(shell_exec("export LANG=C.UTF-8; php {$script} {$arguments}")));
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
