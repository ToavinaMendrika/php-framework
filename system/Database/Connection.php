<?php
namespace Framework\Database;

class Connection
{
    public static function getPDO()
    {
        $host = getenv('DB_HOST');
		$db_name =  getenv('DB_DATABASE');
        $driver = getenv('DB_CONNECTION');
		$info =  $driver .":host=" . $host . ";dbname=" . $db_name . ";charset=utf8mb4";
		return new \PDO($info,  getenv('DB_USERNAME'), getenv('DB_PASSWORD'));
    }
}
