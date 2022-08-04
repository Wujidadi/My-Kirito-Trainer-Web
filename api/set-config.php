<?php

require_once '../configs/env.php';

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
$file = "{$home}/workspaces/MyKiritoCommands/configs/MovementByPlayer.json";
$json = json_encode($data, 448);
file_put_contents($file, $json);

$www = WWW;
require_once "{$www}/others/UpdateChallengeOpponents.php";

echo '設定已覆寫！';
