<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>列出檔案鎖</title>
</head>
<body>
    <pre><?php

require_once 'lib/helpers.php';

$output = recoverCliOutput(shell_exec('php /home/wujidadi/MyKiritoCommands/LsFileLocks'));
echo $output

?></pre>
</body>
</html>