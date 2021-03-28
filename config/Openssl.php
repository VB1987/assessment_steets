<?php

namespace config;

trait Openssl
{
    /**
     * Openssl encryption keys
     * @return string[]
     */
    public static function keys()
    {
        return [
            'cipher' => 'aes-256-cbc',
            'iv' => '1236547980089745',
            'key' => 'Steets_Assessment'
        ];
    }
}