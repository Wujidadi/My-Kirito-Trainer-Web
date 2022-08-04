<?php

$storageDirectory = '/var/www/my/MyKiritoTrainer/storage/AutoChallengeOpponents';
$oppkeyDirectory = '/home/wujidadi/workspaces/MyKirito/storage/flags/AutoChallenge/oppkeys';

foreach ($data as $player => $settings)
{
    # 挑戰對手檔（僅儲存設定進來的對手暱稱列表，以便與新設定比對）
    $challengeOpponentsFile = "{$storageDirectory}/{$player}.opp";
    if (!is_file($challengeOpponentsFile))
    {
        touch($challengeOpponentsFile);
    }

    # 挑戰對手序數檔
    $challengeOpponentKeyFile = "{$oppkeyDirectory}/{$player}.oppkey";

    # 取得舊的自動挑戰對手
    $originalChallengeOpponents = trim(file_get_contents($challengeOpponentsFile));

    # 新設定的自動挑戰對手
    $newChallengeOpponents = $settings['Challenge']['Opponent'];

    # 新舊挑戰對手不同時，更新挑戰對手檔並重置挑戰對手序數
    if ($newChallengeOpponents != $originalChallengeOpponents)
    {
        file_put_contents($challengeOpponentsFile, $newChallengeOpponents);
        file_put_contents($challengeOpponentKeyFile, 0);
    }
}
