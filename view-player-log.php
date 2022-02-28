<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>列出玩家日誌</title>
</head>
<body>
    <pre><?php

require_once 'lib/helpers.php';

$player = $_GET['p'];
$logType = $_GET['t'];
$logLevel = $_GET['v'];
$logDate = $_GET['d'];
$logLine = $_GET['l'];
$output = recoverCliOutput(shell_exec("php /home/wujidadi/MyKiritoCommands/ViewPlayerLog --player={$player} --type={$logType} --level={$logLevel} --date={$logDate} --line={$logLine}"));
echo $output;

?></pre>
</body>
</html>