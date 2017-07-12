<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 11.10.2016
 * Time: 15:37
 */

namespace ShareCloth\Api;


interface ClientInterface
{
    public function __construct($apiSecret, $httpClientConfig = []);

    public function getApiSecret();

    public function itemsList($options);

    public function avatarList($options = []);

}