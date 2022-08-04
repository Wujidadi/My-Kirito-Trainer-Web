<?php

require_once '../configs/env.php';
require_once '../lib/helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST')
{
    header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
    exit;
}

$input = file_get_contents('php://input');
$data = json_decode($input, true);
if (!$data)
{
    header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad Request');
    exit;
}

$home = HOME;
$script = "{$home}/workspaces/MyKirito/cli/Reincarnate.php";
$player = $data['player'];
$set = addslashes(json_encode($data['set'], 320));
try
{
    $output = recoverCliOutput(shell_exec("export LANG=C.UTF-8; php {$script} --player={$player} --set=\"{$set}\" --output"));
    echo $output;
}
catch (Throwable $ex)
{
    $exType = get_class($ex);
    $exCode = $ex->getCode();
    $exMessage = $ex->getMessage();
    echo "{$exType} ({$exCode}) {$exMessage}";
}
