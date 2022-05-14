<?php

chdir(__DIR__);

require_once '../lib/helpers.php';

$style = <<<CSS
* {
    box-sizing: border-box;
}
body {
    margin: 0;
    background-color: #222;
    color: #ddd;
}
pre#command-output {
    background-color: #393939;
    color: #d7d7d7;
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
button, input, textarea, select {
    background-color: #363636;
    color: #ddd;
    border-style: none;
}
button {
    width: 100px;
    height: 25px;
    border-radius: 6px;
    cursor: pointer;
}
button:hover {
    background-color: #424242;
}
::-webkit-scrollbar {
    width: 13px;
    height: 13px;
    background-color: #303030;
}
::-webkit-scrollbar-track {
    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
    border-radius: 4px;
    background-color: #303030;
}
::-webkit-scrollbar-thumb {
    border-radius: 4px;
    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,.3);
    background-color: #555;
}
CSS;
$style = indentHTML($style, 4, 2, true);

return $style;
