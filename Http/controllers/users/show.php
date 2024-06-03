<?php

use Core\App;
use Core\Database;
use Core\Session;

$db = App::resolve(Database::class);


$user = $db->query('select * from users where id = :id', [
    'id' => $_GET['id']
])->findOrFail();

$friendStatus = $db->query('SELECT *
FROM friendships
WHERE (user = :user and friend = :friend)
OR (user = :friend and friend = :user)', [
    'user' => Session::getCurrentUser()['id'],
    'friend' => $_GET['id']
])->find();



view("users/show.view.php", [
    'heading' => 'User Profile',
    'user' => $user,
    'friendStatus' => $friendStatus
]);

