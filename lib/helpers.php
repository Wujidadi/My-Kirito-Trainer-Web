<?php

/**
 * 移除命令行輸出內容中的 ANSI 顏色控制碼及前後空行
 *
 * @param  string  $text  命令行輸出內容
 * @return string|false
 */
function recoverCliOutput($text)
{
    if (is_string($text))
    {
        return preg_replace(
            [
                "/^\n+/",
                "/\033\[38;2;\d{1,3};\d{1,3};\d{1,3}m/",
                "/\033\[0m/",
                "/\n+$/"
            ],
            '',
            $text
        );
    }
    return false;
}
