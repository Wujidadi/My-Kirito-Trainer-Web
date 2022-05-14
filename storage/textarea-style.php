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
div#conf {
    margin: 8px auto auto auto;
    width: calc(100vw - 16px);
    height: 80vh;
}
button, input, textarea, select {
    background-color: #363636;
    color: #ddd;
    border-style: none;
}
textarea {
    width: 100%;
    height: 100%;
    resize: none;
}
div#button-area {
    height: 36px;
    padding-top: 5px;
}
button {
    width: 100px;
    height: 25px;
    cursor: pointer;
    border-radius: 6px;
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
