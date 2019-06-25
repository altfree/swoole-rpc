<?php
namespace Business;
use Illuminate\Database\Capsule\Manager as DB;
class BaseService{

    protected $filter; // 指定字段可作为条件查询
    protected $field;  //指定数据库字段
    protected $transaction; //事物管理

    public function filterField($condition){
        return queryFieldFilter($condition,$this->filter);
    }

    /**
     *  开始一个事务处理
     *  @param string  $transactionId 事务id
     */
    public function createTraction($transactionId)
    {
        $this->transaction=$transactionId;
        DB::table("XA START '$transactionId'");
    }

     /**
     *  事务事务处理
     *  @param string  $transactionId 事务id
     */
    public function handeTraction()
    {
        DB::table("XA END '$this->transaction'");
        DB::table("XA COMMIT '$this->transaction'");
        DB::table("XA PREPARE '$this->transaction'");
    }


     /**
     *  事务回滚
     *  @param string  $transactionId 事务id
     */
    public function rollbackTraction()
    {
        DB::table("XA ROLLBACK '$this->transaction'");
    }


    public function __isset($func){

        if (!isset($this->$func)) {
            return json_encode(["code" => 1, "data" => $callData, "msg" =>$func.'方法不存在'],JSON_UNESCAPED_UNICODE);
        }
    }



   





}
