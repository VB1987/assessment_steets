<?php

namespace config;

trait Database
{
    /**
     * Make connection to database
     * @return \mysqli
     */
    public static function makeConnection()
    {
        $user = 'root';
        $password = '';
        $database = 'steets_assessment';
        $host = 'localhost';

        try {
            return new \mysqli($host, $user, $password, $database);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}