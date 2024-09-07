<?php
class Token {
    public static function createToken(){
        return $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));
    }
}