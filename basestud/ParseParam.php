<?php
namespace BaseStud;

class ParseParam
{

    private $service;
    private $action;
    private $arg;

    public function __construct($param)
    {
        $arrParam = json_decode($param,true);
        $this->service = $arrParam["serv"] ? "Business".$arrParam["serv"] : "";
        $this->action = $arrParam["func"] ? $arrParam["func"] : "";
        $this->arg = $arrParam["arg"] ? $arrParam["arg"] : [];
    }

    public function handle()
    {
        //判断服务类是否定义
        if(!class_exists($this->service)){
            return json_encode(["code" => 1, "data" =>[], "msg" =>$this->service."服务不存在"],JSON_UNESCAPED_UNICODE);
        }
        //判断服务方法是否定义
        if(!method_exists($this->service,$this->action)){
            return json_encode(["code" => 1, "data" =>[], "msg" =>$this->service.'服务下的'.$this->action."方法不存在"],JSON_UNESCAPED_UNICODE);
        }

        try {
            $class=new $this->service;
            $callData = call_user_func_array(array($class, $this->action), $this->arg);
            return json_encode($callData,JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            return json_encode(["code" => 1, "data" => $callData, "msg" => $e->getMessage()],JSON_UNESCAPED_UNICODE);
        }
    }

}
