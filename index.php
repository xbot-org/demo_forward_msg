<?php

$jsonString = file_get_contents("php://input");

$msg = json_decode($jsonString, true);

switch ($msg['type']) {
    case 'MT_RECV_VIDEO_MSG':
        // 固定监听某个群的消息
        if ($msg['data']['room_wxid'] == 'xxx@chatroom') {
            // 转发给特定某个群或联系人
            forward('xxxx', $msg['data']['msgid']);
            // 转发给多个
            forward('xxxx2', $msg['data']['msgid']);
        }
    case 'MT_RECV_PICTURE_MSG':
        forward('xxxx2', $msg['data']['msgid']);
}

echo '{}';

function forward($wxid, $msgId) {
    post(json_encode([
        'client_id' => 1,
        'is_sync' => 1,
        'data' => [
            'to_wxid' => $wxid,
            'msgid' => $msgId,
        ],
        'type' => 'MT_FORWARD_ANY_MSG',
    ]));
}

function post($data) {
    $opts = array('http' =>
        array(
            'method'  => 'POST',
            'header'  => 'Content-Type: application/json',
            'content' => $data
        )
    );

    $context  = stream_context_create($opts);

    file_get_contents('http://127.0.0.1:5557', false, $context);
}