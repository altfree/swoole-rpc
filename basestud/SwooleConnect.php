<?php
namespace BaseStud;

use Swoole\Server;
use BaseStud\ParseParam;

class SwooleConnect{

    private $server;
    public function __construct(string $host='127.0.0.1',int $port=1701,string $protocol){
        // \Swoole\Runtime::enableCoroutine();
        $server=new \swoole_server($host,$port, SWOOLE_BASE, SWOOLE_SOCK_TCP | SWOOLE_SSL);
        $this->server=&$server;
        // $this->server->set(array(
        //     'worker_num'=>1,
        //     'reactor_num'=>5,
        //     'ssl_cert_file' =>"/home/dongjiebo/rpc/rpc/server-cert.pem",
        //     'ssl_key_file' => "/home/dongjiebo/rpc/rpc/server-key.pem",
        //     'ssl_verify_peer' => true,
        //     'ssl_allow_self_signed' => true,
        //     'ssl_client_cert_file' => "/home/dongjiebo/rpc/rpc/ca-cert.pem",
        //     'ssl_verify_depth' => 10,
        //     'max_conn'=>100,
        // ));
        
        $this->server->set(array(
            'worker_num'=>1,
            'reactor_num'=>5,
            'ssl_cert_file' =>dirname(__FILE__).'/../cert/server.crt',
            'ssl_key_file' => dirname(__FILE__).'/../cert/server.key',
            'ssl_verify_peer' => true,
            'ssl_allow_self_signed' => true,
            'ssl_client_cert_file' => dirname(__FILE__).'/../cert/ca.crt',
            'ssl_verify_depth' => 10,
            'max_conn'=>100,
        ));

            
        $server->on('connect', function (\swoole_server $server, $fd) {
            $conInfo=$server->getClientInfo($fd);
            echo "客户端:".$conInfo['remote_ip']."连接成功\n";

        });
    }

    //数据接受
    private function listenServer()
    {
        // $server=$this->server;
        $this->server->on("receive",function(\swoole_server $server,$fd, $from_id, $data){
            //开启协程
            // go(function()use($data,$server,$fd){
                $conInfo=$server->getClientInfo($fd);
                echo date('Y-m-d H:i:s').' ip:'.$conInfo['remote_ip'].' 请求参数:'.$data."\n";
                $parse=new ParseParam($data);
                $responseMsg=$parse->handle();
                $this->send($responseMsg,$server,$fd);
            // });
        });

        $this->server->on('close',function($server,$fd){
            $conInfo=$server->getClientInfo($fd);
            echo "客户端:".$conInfo['remote_ip']."断开连接\n";
        });

        return ;
    }


    public function send($data,$server,$fd)
    {
        return $server->send($fd,$data);
    }

    //启动监听服务
    public function onServer()
    {
        $this->listenServer();
        $this->server->start();

    }





}
