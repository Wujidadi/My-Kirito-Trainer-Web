<?php

require_once 'storage/token.php';

if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW']) || $_SERVER['PHP_AUTH_USER'] !== HTTP_USERNAME || $_SERVER['PHP_AUTH_PW'] !== HTTP_PASSWORD)
{
    header('www-authenticate: Basic realm="Wujidadi\'s MyKirito Trainer"');
    header($_SERVER['SERVER_PROTOCOL'] . ' 401 Unauthorized');
    exit;
}
else
{
    $file = '/home/wujidadi/workspaces/MyKirito/storage/configs/Players.json';
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
        'character' => 'heathcliff',
        'rattrs' => [
            'hp' => 10000,
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

    $page = <<<HTML
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>令特定玩家轉生</title>
        <style>
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
            <div id="button-area">
                <button id="submit">Submit</button>
                <button id="back">Back</button>
            </div>
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
                location.href = '/';
            });
        </script>
    </body>
    </html>
    HTML;
    echo $page;
}
