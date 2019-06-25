# swoole-rpc服务调用
### business模块添加公共业务处理
```php
<?php
namespace Business;
use Models\Order;
class OrderService{
    static function test($a,$b)
    {
        return $a+$b;
    }
    public function orderInfo($condition,$field)
    {
        $new=new Order;
        return $new->infoOne($condition,$field);
    }
}
```
###  客户端请求事例：
```php
<?php
$client = new swoole_client(SWOOLE_SOCK_TCP);
if (!$client->connect('ip地址', 端口号, -1))
{
    exit("connect failed. Error: {$client->errCode}\n");
}
$args=[[],['id','order_no']];
//$args参数为数组
//serv类名称
//func请求方法
$x=['serv'=>'Business\OrderService','func'=>'orderInfo','arg'=>$args];
$client->send($x);
echo $client->recv();
$client->close();
```
