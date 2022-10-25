<?php

$_ENV = parse_ini_file(__DIR__ . '/../.env');

date_default_timezone_set("Asia/Taipei");

spl_autoload_register();

$database = [
    'driver'    => $_ENV['DB_DRIVER'],
    'host'      => $_ENV['DB_HOST'],
    'database'  => $_ENV['DB_DATABASE'],
    'username'  => $_ENV['DB_USERNAME'],
    'password'  => $_ENV['DB_PASSWORD'],
    'charset'   => $_ENV['DB_CHARSET'],
    'collation' => $_ENV['DB_COLLATION'],
    'prefix'    => $_ENV['DB_PREFIX'],
];

use Illuminate\Database\Capsule\Manager as DB;

$db = new DB;

// 建立連線
$db->addConnection($database);

// 設定成全域可訪問
$db->setAsGlobal();

// 啟動Eloquent
$db->bootEloquent();
