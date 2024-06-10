<?php

use Core\App;
use Core\Database;
use Core\Session;

header('Content-Type: application/json');

$user = Session::getCurrentUser();
$db = App::resolve(Database::class);

$json = file_get_contents('php://input');
$data = json_decode($json, true);

$isMember = $db->query('select * from chatroom_members where user_id = :userId and chatroom_id = :chatroomId', [
    'userId' => $user['id'],
    'chatroomId' => $_REQUEST['chatroomId']
])->find();


$messages = $isMember ? $db->query('SELECT chatroom_texts.*, users.id, users.username, users.image userImage
FROM chatroom_texts 
JOIN users ON users.id = chatroom_texts.user_id 
WHERE chatroom_texts.chatroom_id = :chatroomId 
ORDER BY chatroom_texts.created_at DESC 
LIMIT 10', [
    'chatroomId' => $_REQUEST['chatroomId']
])->get() : false;

$data['messages'] = $messages;

$data['userId'] = $user['id'];

echo json_encode($data, JSON_UNESCAPED_UNICODE);