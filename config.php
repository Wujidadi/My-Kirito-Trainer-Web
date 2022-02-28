<?php

$file = '/home/wujidadi/MyKiritoCommands/configs/MovementByPlayer.json';
$json = file_get_contents($file);

$page = <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyKirito Trainer 設定</title>
    <style>
        div#conf {
            margin: auto;
            width: 90vw;
            height: 90vh;
        }
        textarea {
            width: 100%;
            height: 100%;
            resize: none;
        }
    </style>
</head>
<body>
    <div id="conf">
        <textarea id="jsonconf">{$json}</textarea>
        <button id="submit">Submit</button>
    </div>
    <script>
        const btnSubmit = document.querySelector('#submit');
        const txtConfig = document.querySelector('#jsonconf');
        btnSubmit.addEventListener('click', function() {
            if (confirm('確定覆寫設定？')) {
                const jsonConfig = txtConfig.value;
                fetch('api/set-config', {
                    method: 'POST',
                    headers: {
                        'content-type': 'application/json'
                    },
                    body: jsonConfig
                })
                .then(response => response.text())
                .then(response => {
                    alert(response);
                })
                .catch(error => {
                    alert(error);
                });
            }
        });
    </script>
</body>
</html>
HTML;

echo $page;
