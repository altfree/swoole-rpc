<?php
require 'vendor/autoload.php';
use Illuminate\Database\Capsule\Manager;
use BaseStud\SwooleConnect;
// orm模型初始化
$orm = new Manager;
$orm->addConnection(require 'config/database.php');
$orm->bootEloquent();
$swoole=new SwooleConnect("0.0.0.0",9502,"tcp");
$swoole->onServer();

