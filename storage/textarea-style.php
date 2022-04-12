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
div#conf {
    margin: 8px auto auto auto;
    width: calc(100vw - 16px);
    height: 80vh;
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
}
CSS;
$style = indentHTML($style, 4, 2, true);

return $style;
