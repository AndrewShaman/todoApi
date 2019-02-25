<?php
/**
 * Created by PhpStorm.
 * User: staj
 * Date: 28.01.19
 * Time: 12:52
 */

namespace Tests;


trait apiAs
{
    protected function apiAs($user, $method, $uri, array $data = [], array $headers = [])
    {
        $headers = array_merge([
            'Authorization' => 'Bearer '.\JWTAuth::fromUser($user),
        ], $headers);

        return $this->api($method, $uri, $data, $headers);
    }

    protected function api($method, $uri, array $data = [], array $headers = [])
    {
        return $this->json($method, $uri, $data, $headers);
    }
}