<?php

namespace App\Client;

use App\Client\User;

class Client{

    private string $url;

    function __construct($url){
        $this->url = $url;
    }

    public function getToken(User $user)
    {
        $body = [
            'username' => $user->getUsername(),
            'password' => $user->getPassword()];
        $ch = curl_init($this->url . '/login');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body, JSON_UNESCAPED_UNICODE));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);

        $user->setToken(json_decode($response)->token);
        return $response;
    }

    public function addUser(string $username, string $password)
    {
        $body = [
            'username' => $username,
            'password' => $password];
        $ch = curl_init($this->url . '/user/reg');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body, JSON_UNESCAPED_UNICODE));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }

    public function getUserById(int $id)
    {
        $ch = curl_init($this->url . '/user/get/' . $id);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response);
    }

    public function getAllUsers()
    {
        $ch = curl_init($this->url . '/user/get_all');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }

    public function putUser(User $user, string $new_username = '', string $old_password = '', string $new_password = '')
    {
        $body = [
            'username' => $new_username,
            'old_password' => $old_password,
            'password' => $new_password];

        if($new_username != '')
        {
            $body += ['username' => $new_username,];
        }
        if($new_password != '' && $old_password != '')
        {
            $body += [
                'old_password' => $old_password,
                'new_password' => $new_password];
        }

        $ch = curl_init($this->url . '/user/put');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Token:' . $user->getToken()));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body, JSON_UNESCAPED_UNICODE));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }

    public function deleteUser(User $user)
    {
        $ch = curl_init($this->url . '/user/delete');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Token:' . $user->getToken()));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }
}