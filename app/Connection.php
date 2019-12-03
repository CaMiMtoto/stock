<?php


namespace App;


class Connection
{
    static function getConnection()
    {
        $host = env('DB_HOST');
        $user = env('DB_USERNAME', 'root');
        $password = env('DB_PASSWORD', '');
        $db = env('DB_DATABASE', 'stock');
        $con = mysqli_connect($host, $user, $password, $db) or die('Not connected');
        return $con;
    }
}
