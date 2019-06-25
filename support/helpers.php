<?php
/**
 *  @param array $param  传入的条件
 *  @param array $filter 指定的的条件字段
 *
 */
function queryFieldFilter(array $param, array $filter)
{
    $param = array_filter($param, function ($key) use ($filter) {
        return array_filter($filter, function ($value) use ($key) {
            if ($value == $key && !is_numeric($key)) {
                return $value;
            }

        });
    }, ARRAY_FILTER_USE_KEY);

    return $param;

}

/**
 *  @param string $msg  提示原因
 *
 */
function responseNotice($msg)
{

    $callParams['data'] = [];
    $callParams['msg'] = $msg;
    $callParams['code'] = 1;
    return $callParams;

}

function response($param)
{

    $callParams['data'] = $param;
    $callParams['code'] = 0;
    return $callParams;

}

function formatting($pageData)
{
    $call['page']=$pageData->currentPage(); //当前页数
    $call['count']=$pageData->count();  //当前页面数量
    $call['pageCount']=$pageData->lastPage();// 总的页数
    $call['pageCount']=$pageData->total();// 数据总量
    $call['items']=array_values($pageData->items());// 数据
    return $call;
}
