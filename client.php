<?php
$client = new swoole_client(SWOOLE_SOCK_TCP | SWOOLE_SSL);
$client->set(array(
    'ssl_cert_file' => '/Users/a/sslcrt/yyhealth/client.crt',
    'ssl_key_file' => '/Users/a/sslcrt/yyhealth/client.pem',
    'ssl_allow_self_signed' => true,
    'ssl_verify_peer' => true,
    'ssl_cafile' => '/Users/a/sslcrt/yyhealth/ca.crt',
    'ssl_passphrase'=>'xiaobai',
));
if (!$client->connect('192.168.1.58', 9502, -1))
{
    exit("connect failed. Error: {$client->errCode}\n");
}
$args = [[], ['id', 'order_no']];
$x = json_encode(['serv' => "\KouBeiService", 'func' => 'getAppMerchatList', 'arg' =>[['token'=>'201905BB4614d96e18c94770b2d8074d4de5fE16']]]);

$client->send($x);
echo $client->recv();
$client->close();

