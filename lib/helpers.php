<?php

function recoverCliOutput($text)
{
    return preg_replace(
        [
            "/^\n+/",
            "/\033\[38;2;\d{1,3};\d{1,3};\d{1,3}m/",
            "/\033\[0m/"
        ],
        '',
        $text
    );
}
