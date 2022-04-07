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
    $selectOptionIndent = str_repeat(' ', 12);
    $selectOptionsByPlayer = "<option value=\"{$nullMark}\" disabled>====玩家暱稱====</option>\n";
    foreach ($players as $player)
    {
        $selectOptionsByPlayer .= "{$selectOptionIndent}<option value=\"{$player}\">{$player}</option>\n";
    }
    $selectOptionsByPlayer = preg_replace('/\n$/', '', $selectOptionsByPlayer);

    $page = <<<HTML
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>MyKirito Trainer</title>
        <style>
            button, input, select {
                margin-bottom: 5px;
            }
            button.feature {
                font-size: 16px;
                width: 250px;
            }
            input {
                text-align: center;
                height: 18px;
            }
            input, select.input {
                width: 180px;
            }
            select.input {
                height: 22px;
            }
        </style>
    </head>
    <body>
        <fieldset>
            <button class="feature" id="token">玩家令牌設定</button>
        </fieldset>
        <fieldset>
            <button class="feature" id="config">自動行動與挑戰設定</button>
        </fieldset>
        <fieldset>
            <button class="feature" id="ps">檢查進程</button>
        </fieldset>
        <fieldset>
            <button class="feature" id="ls">列出檔案鎖</button>
        </fieldset>
        <fieldset>
            <button class="feature" id="run">執行自動腳本</button>
        </fieldset>
        <fieldset>
            <button class="feature" id="status">查詢特定玩家當前狀態總覽</button><br />
            <!-- 玩家：<input type="input" id="player-to-get-status"> -->
            玩家：<select class="input" id="player-to-get-status">
                {$selectOptionsByPlayer}
            </select>
        </fieldset>
        <fieldset>
            <button class="feature" id="stop">停止特定玩家的自動腳本</button><br />
            <!-- 玩家：<input type="input" id="player-to-stop"> -->
            玩家：<select class="input" id="player-to-stop">
                {$selectOptionsByPlayer}
            </select>
        </fieldset>
        <fieldset>
            <button class="feature" id="reincarnate">令特定玩家轉生</button>
        </fieldset>
        <fieldset>
            <button class="feature" id="kill">停止所有自動腳本</button>
        </fieldset>
        <fieldset>
            <button class="feature" id="view-log">列出玩家日誌</button><br />
            <!-- 玩家：<input type="input" id="player-to-view-log"><br /> -->
            玩家：<select class="input" id="player-to-view-log">
                {$selectOptionsByPlayer}
            </select><br />
            日誌種類：<select id="log-type">
                <option value="AutoAction">自動行動</option>
                <option value="AutoChallenge">自動挑戰</option>
            </select><br />
            日誌等級：<select id="log-level">
                <option value="brief">簡要</option>
                <option value="detail">詳細</option>
                <option value="notify">通知</option>
            </select><br />
            日期：<input type="date" id="log-date"><br />
            行數：<input type="number" id="log-line" step="1" min="1">
        </fieldset>

        <script>
            const nullMark = '{$nullMark}';
            const btnToken = document.querySelector('#token');
            const btnConf = document.querySelector('#config');
            const btnPs = document.querySelector('#ps');
            const btnLs = document.querySelector('#ls');
            const btnRun = document.querySelector('#run');
            const btnStatus = document.querySelector('#status');
            const inputPlayerToGetStatus = document.querySelector('#player-to-get-status');
            const btnStop = document.querySelector('#stop');
            const inputPlayerToStop = document.querySelector('#player-to-stop');
            const btnReincarnate = document.querySelector('#reincarnate');
            const btnKill = document.querySelector('#kill');
            const btnViewLog = document.querySelector('#view-log');
            const inputPlayerToViewLog = document.querySelector('#player-to-view-log');
            const selectLogType = document.querySelector('#log-type');
            const selectLogLevel = document.querySelector('#log-level');
            const inputLogDate = document.querySelector('#log-date');
            const inputLogLine = document.querySelector('#log-line');
            const logInputs = [
                // inputPlayerToViewLog,
                inputLogDate,
                inputLogLine
            ];

            inputPlayerToGetStatus.value = getLocalStorageItemOrNullMark('PlayerToGetStatus');

            inputPlayerToStop.value = getLocalStorageItemOrNullMark('PlayerToStop');

            inputPlayerToViewLog.value = getLocalStorageItemOrNullMark('PlayerToViewLog');
            selectLogType.value = (localStorage.getItem('LogType') !== null && localStorage.getItem('LogType') !== '') ? localStorage.getItem('LogType') : 'AutoAction';
            selectLogLevel.value = (localStorage.getItem('LogLevel') !== null && localStorage.getItem('LogLevel') !== '') ? localStorage.getItem('LogLevel') : 'brief';
            inputLogDate.value = (localStorage.getItem('LogDate') !== null && localStorage.getItem('LogDate') !== '') ? localStorage.getItem('LogDate') : getToday();
            inputLogLine.value = (localStorage.getItem('LogLine') !== null && localStorage.getItem('LogLine') !== '') ? localStorage.getItem('LogLine') : 25;

            function getLocalStorageItemOrNullMark(key) {
                return (localStorage.getItem(key) !== null) ? localStorage.getItem(key) : nullMark;
            }

            function getToday() {
                const date = new Date();
                const year = date.getFullYear();
                const rawMonth = date.getMonth() + 1;
                let month;
                const day = date.getDate();
                if (rawMonth < 10) {
                    month = '0' + rawMonth.toString();
                } else {
                    month = rawMonth.toString();
                }
                return `\${year}-\${month}-\${day}`;
            }

            btnToken.addEventListener('click', function() {
                location.href = 'token';
            });

            btnConf.addEventListener('click', function() {
                location.href = 'config';
            });

            btnPs.addEventListener('click', function() {
                location.href = 'ps-mykirito';
            });

            btnLs.addEventListener('click', function() {
                location.href = 'ls-filelocks';
            });

            btnRun.addEventListener('click', function() {
                location.href = 'auto-batch';
            });

            btnStatus.addEventListener('click', function() {
                getPlayerStatus();
            });
            // inputPlayerToGetStatus.addEventListener('keypress', function(event) {
            //     if (event.keyCode == 13) {
            //         getPlayerStatus();
            //     }
            // });
            function getPlayerStatus() {
                if (inputPlayerToGetStatus.value !== nullMark) {
                    const playerToGetStatus = inputPlayerToGetStatus.value;
                    localStorage.setItem('PlayerToGetStatus', playerToGetStatus);
                    location.href = `get-player-status?p=\${playerToGetStatus}`;
                } else {
                    alert('須選擇玩家暱稱！');
                }
            }

            btnStop.addEventListener('click', function() {
                stopAutoProcessesByPlayer();
            });
            // inputPlayerToStop.addEventListener('keypress', function(event) {
            //     if (event.keyCode == 13) {
            //         stopAutoProcessesByPlayer();
            //     }
            // });
            function stopAutoProcessesByPlayer() {
                if (inputPlayerToStop.value !== nullMark) {
                    const playerToStop = inputPlayerToStop.value;
                    localStorage.setItem('PlayerToStop', playerToStop);
                    location.href = `stop-player-auto-process?p=\${playerToStop}`;
                } else {
                    alert('須選擇玩家暱稱！');
                }
            }

            btnReincarnate.addEventListener('click', function() {
                location.href = 'reincarnate';
            });

            btnKill.addEventListener('click', function() {
                location.href = 'kill-auto-process';
            });

            btnViewLog.addEventListener('click', function() {
                viewPlayerLog();
            });
            logInputs.forEach(elm => {
                elm.addEventListener('keypress', function(event) {
                    if (event.keyCode == 13) {
                        viewPlayerLog();
                    }
                });
            });
            function viewPlayerLog() {
                if (inputPlayerToViewLog.value !== nullMark) {
                    const playerToViewLog = inputPlayerToViewLog.value;
                    const logType = selectLogType.value;
                    const logLevel = selectLogLevel.value;
                    const logDate = inputLogDate.value;
                    const logLine = inputLogLine.value;
                    localStorage.setItem('PlayerToViewLog', playerToViewLog);
                    localStorage.setItem('LogType', logType);
                    localStorage.setItem('LogLevel', logLevel);
                    localStorage.setItem('LogDate', logDate);
                    localStorage.setItem('LogLine', logLine);
                    location.href = `view-player-log?p=\${playerToViewLog}&t=\${logType}&v=\${logLevel}&d=\${logDate}&l=\${logLine}`;
                } else {
                    alert('須選擇玩家暱稱！');
                }
            }
        </script>
    </body>
    </html>
    HTML;
    echo $page;
}
