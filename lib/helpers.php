<?php

/**
 * 移除命令行輸出內容中的 ANSI 顏色控制碼及前後空行
 *
 * @param  string  $text  命令行輸出內容
 * @return string|false
 */
function recoverCliOutput($text)
{
    $output = false;

    if (is_string($text))
    {
        $output = preg_replace(
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

    return $output;
}

/**
 * 指定空格數和縮排層級，產生由空格組成的縮排字串
 *
 * @param  integer  $space  每級縮排的空格數
 * @param  integer  $step   縮排層級
 * @return string[]         返回 `indent`（本層級縮排）及 `lastIndent`（上一級縮排） 
 */
function getIndent($space = 4, $step = 2)
{
    $indent = '    ';
    $lastIndent = '';

    if (is_int($space) && is_int($step))
    {
        $lastStep = ($step - 1 >= 0) ? ($step - 1) : 0;
        $indent = str_repeat(' ', $space * $step);
        $lastIndent = str_repeat(' ', $space * $lastStep);
    }

    return compact('indent', 'lastIndent');
}

/**
 * 輸入原始 HTML 碼，並指定空格數和縮排層級，產生縮排好的 HTML 碼
 *
 * @param string   $html   原始 HTML 碼
 * @param integer  $space  每級縮排的空格數
 * @param integer  $step   縮排層級
 * @param boolean  $whole  尾部是否閉合
 * @return string
 */
function indentHTML($html, $space, $step, $whole = true)
{
    $elm = '';

    if (is_string($html) && is_int($space) && is_int($step))
    {
        extract(getIndent($space, $step));

        # 先逐行加縮排，再於首行前換行加縮排
        $elm = preg_replace(
            [
                '/\n/',
                '/^/'
            ],
            [
                "\n{$indent}",
                "\n{$indent}"
            ],
            $html
        );

        # 若尾部閉合，換行並附加上一級關閉標籤所需的縮排
        if ($whole)
        {
            $elm = preg_replace('/$/', "\n{$lastIndent}", $elm);
        }

        unset($indent);
        unset($lastIndent);
    }

    return $elm;
}

/**
 * 代入命令行輸出內容，產生帶返回鍵區域的各個網頁元素
 *
 * @param  string  $output  命令行輸出內容
 * @return string
 */
function outputWithBackButtonArea($output)
{
    $elm = '';

    if (is_string($output))
    {
        $space = 4;
        $step = 1;

        $outputArea = indentHTML('<pre id="command-output">', $space, $step, false) . $output . '</pre>';

        $restArea = <<<HTML
        <div id="button-area">
            <button id="back">Back</button>
        </div>
        <script>
            const btnBack = document.querySelector('#back');
            btnBack.addEventListener('click', function() {
                location.href = '/';
            });
        </script>
        HTML;
        $restArea = indentHTML($restArea, $space, $step, true);

        $elm = $outputArea . $restArea;
        
        unset($outputArea);
        unset($restArea);
    }

    return $elm;
}

/**
 * 產生帶確認及返回鍵區域的各個網頁元素
 *
 * @param  integer  $space  每級縮排的空格數
 * @param  integer  $step   縮排層級
 * @return string
 */
function submitAndBackButtonArea($space, $step)
{
    $elm = <<<HTML
    <div id="button-area">
        <button id="submit">Submit</button>
        <button id="back">Back</button>
    </div>
    HTML;
    $elm = indentHTML($elm, $space, $step, false);
    $elm = preg_replace('/^\n +/', '', $elm);    // 移除頭部縮排

    return $elm;
}
