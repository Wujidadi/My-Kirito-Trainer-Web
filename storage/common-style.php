<?php

chdir(__DIR__);

require_once '../lib/helpers.php';

$style = <<<CSS
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
    /* white-space: pre-wrap; */
    /* word-break: break-all; */
    overflow-x: auto;
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
CSS;
$style = indentHTML($style, 4, 2, true);

return $style;
