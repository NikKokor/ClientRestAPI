<?php

require_once dirname(__DIR__).'/ClientRestAPI/vendor/autoload.php';

use App\Client\User;
use App\Client\Client;

$client = new Client('http://195.140.146.82');

echo ($client->addUser('user', 'user'));

$user = new User('user', 'user');

echo ($client->getToken($user));
echo ($client->getAllUsers());
echo ($client->putUser($user, new_username: 'user123'));
echo ($client->getAllUsers());