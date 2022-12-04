<?php

require_once 'configs/env.php';
require_once 'storage/token.php';

if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW']) || $_SERVER['PHP_AUTH_USER'] !== HTTP_USERNAME || $_SERVER['PHP_AUTH_PW'] !== HTTP_PASSWORD)
{
    $basicRealm = BASIC_REALM;
    header("www-authenticate: Basic realm={$basicRealm}");
    header($_SERVER['SERVER_PROTOCOL'] . ' 401 Unauthorized');
    exit;
}
else
{
    require_once 'lib/helpers.php';

    $title = '令特定玩家轉生';

    $home = HOME;
    $file = "{$home}/workspaces/MyKirito/storage/configs/Players.json";
    $json = file_get_contents($file);
    $playerConfigs = json_decode($json, true);
    $players = array_keys($playerConfigs);

    $nullMark = '(null)';
    $selectOptionIndent = str_repeat(' ', 16);
    $selectOptionsByPlayer = "<option value=\"{$nullMark}\" disabled selected>====玩家暱稱====</option>\n";
    foreach ($players as $player)
    {
        $selectOptionsByPlayer .= "{$selectOptionIndent}<option value=\"{$player}\">{$player}</option>\n";
    }
    $selectOptionsByPlayer = preg_replace('/\n$/', '', $selectOptionsByPlayer);

    $defaultSetting = json_encode([
        'character' => '',
        'rattrs' => [
            'hp' => 0,
            'atk' => 0,
            'def' => 0,
            'stm' => 0,
            'agi' => 0,
            'spd' => 0,
            'tec' => 0,
            'int' => 0,
            'lck' => 0
        ],
        'useReset' => false,
        'useResetBoss' => false
    ], 448);

    $buttonArea = submitAndBackButtonArea(4, 2);

    $page = <<<HTML
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{$title}</title>
        <style>
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
            input {
                text-align: center;
            }
            textarea {
                width: 100%;
                height: 90%;
                margin-top: 5px;
                resize: none;
            }
            div#button-area {
                height: 36px;
                padding-top: 5px;
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
        </style>
    </head>
    <body>
        <div id="conf">
            <!-- <div>玩家：<input type="input" id="player"></div> -->
            <div>
                玩家：<select class="input" id="player">
                    {$selectOptionsByPlayer}
                </select>
            </div>
            <textarea id="jsonconf">{$defaultSetting}</textarea>
            {$buttonArea}
        </div>
        <script>
            const nullMark = '{$nullMark}';
            const iptPlayer = document.querySelector('#player');
            const txtConfig = document.querySelector('#jsonconf');
            const btnSubmit = document.querySelector('#submit');
            const btnBack = document.querySelector('#back');

            btnSubmit.addEventListener('click', function() {
                if (iptPlayer.value !== nullMark) {
                    const player = iptPlayer.value;
                    const jsonConfig = txtConfig.value;
                    if (player === '') {
                        alert('須填寫玩家暱稱！');
                        return;
                    }
                    if (jsonConfig === '') {
                        alert('須以正確的 JSON 格式填寫轉生配置！');
                        return;
                    }
                    try {
                        JSON.parse(jsonConfig);
                    } catch (error) {
                        alert('須以正確的 JSON 格式填寫轉生配置！');
                        return;
                    }
                    if (confirm('確定以此配置轉生？')) {
                        fetch('api/reincarnate', {
                            method: 'POST',
                            headers: {
                                'content-type': 'application/json'
                            },
                            body: JSON.stringify({
                                'player': player,
                                'set': JSON.parse(jsonConfig)
                            })
                        })
                        .then(response => response.text())
                        .then(response => {
                            alert(response);
                            if (/^轉生成功/.test(response)) {
                                location.href = '/';
                            }
                        })
                        .catch(error => {
                            alert(error);
                        });
                    }
                } else {
                    alert('須選擇玩家暱稱！');
                }
            });

            btnBack.addEventListener('click', function() {
                if (confirm('確定返回？')) {
                    location.href = '/';
                }
            });
        </script>
    </body>
    </html>
    HTML;

    echo $page;
}
