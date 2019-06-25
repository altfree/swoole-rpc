<?php
namespace Business;

use Support\Alipay\Client;
use Support\Alipay\Request\AppPay;
use Support\Alipay\Request\GetMerchat;
use Support\Alipay\Request\KoubeiTicketCodeUse;
use Support\Alipay\Request\KoubeiQueryStore;
use Support\Alipay\Request\KoubeiCreateStore;
use Support\Alipay\Request\KoubeiUpdateStoreInfo;
use Support\Alipay\Request\KoubeiUploadImageStore;

class KouBeiService
{

    /**
     *  上传门店图片视频信息
     * 
     */
    public function uploadImgStore(array $param,string $authToken=null)
    {
        if (empty($param['image_type'])) {
            return responseNotice("缺少图片/视频格式");
        }
        if (empty($param['image_content'])) {
            return responseNotice("缺少图片/视频名称");
        }
        if (empty($param['image_name'])) {
            return responseNotice("缺少图片/视频二进制内容");
        }
        $requestClass = new KoubeiUploadImageStore;
        $requestClass->setBizContent($param);
        return  $this->reqeustApi($requestClass,$authToken);
    }

    /**
     *  创建口碑门店
     * 
     */
    public function createStore(array $param,string $authToken=null)
    {
        if (empty($param['store_id'])) {
            return responseNotice("缺少创建外部门店id编号");
        }
        if (empty($param['category_id'])) {
            return responseNotice("缺少创建订单所需的经营类目id,请看:https://docs.open.alipay.com/205/104497");
        }
        if (empty($param['main_shop_name'])) {
            return responseNotice("主门店名称");
        }
        if (empty($param['province_code'])) {
            return responseNotice("缺少创建门店省份编码");
        }
        if (empty($param['city_code'])) {
            return responseNotice("缺少创建门店城市编码");
        }
        if (empty($param['district_code'])) {
            return responseNotice("缺少创建门店区县编码");
        }
        if (empty($param['address'])) {
            return responseNotice("缺少门店详细地址");
        }
        if (empty($param['longitude'])) {
            return responseNotice("缺少门店所属经度信息");
        }
        if (empty($param['latitude'])) {
            return responseNotice("缺少门店所属纬度信息");
        }
        if (empty($param['contact_number'])) {
            return responseNotice("缺少门店联系人电话");
        }
        if (empty($param['main_image'])) {
            return responseNotice("缺少门店首图");
        }
        if (empty($param['isv_uid'])) {
            return responseNotice("缺少创建门店反佣金id");
        }
        if (empty($param['request_id'])) {
            return responseNotice("缺少请求码");
        }
        
        $param['biz_version']='2.0'; //isv识别
        $requestClass = new KoubeiCreateStore;
        $requestClass->setBizContent($param);
        return  $this->reqeustApi($requestClass,$authToken);
        
    }
    /**
     *  修改门店信息
     * 
     */
    public function updateStoreInfo(array $param,string $authToken=null)
    {
        if (empty($param['shop_id'])) {
            return responseNotice("缺少门店id编号");
        }
        $param['biz_version']='2.0'; //isv识别
        $requestClass = new KoubeiUpdateStoreInfo;
        $requestClass->setBizContent($param);
        return  $this->reqeustApi($requestClass,$authToken);
    }

    /**
     *  查询门店信息
     * 
     */
    public function queryStoreInfo(array $param,string $authToken=null)
    {
        if (empty($param['shop_id'])) {
            return responseNotice("缺少门店id编号");
        }
        $requestClass = new KoubeiQueryStore;
        $requestClass->setBizContent($param);
        return  $this->reqeustApi($requestClass,$authToken);
    }
    /**
     *  口碑核销
     *
     */
    public function useTicketCode(array $param,string $authToken=null)
    {
        if (empty($param['request_id'])) {
            return responseNotice("缺少外部请求号");
        }
        if (empty($param['ticket_code'])) {
            return responseNotice("缺少核销码参数");
        }

        if (empty($param['shop_id'])) {
            return responseNotice("缺少店铺id");
        }
        $useCodeClass = new KoubeiTicketCodeUse;
        $useCodeClass->setBizContent($param);
        return  $this->reqeustApi($useCodeClass,$authToken);
    }
    /**
     *  核销码查询
     * 
     */
    public function checkTicketCode(array $param,string $authToken=null)
    {
        if (empty($param['ticket_code'])) {
            return responseNotice("缺少核销码信息");
        }
        if (empty($param['shop_id'])) {
            return responseNotice("缺少店铺id");
        }
        $useCodeClass = new KoubeiTicketCodeUse;
        $useCodeClass->setBizContent($param);
        return $this->reqeustApi($useCodeClass,$param['token']);
    }
     /**
     *  app支付
     * 
     */
    public function appPay(array $param,string $authToken=null)
    {
        if (empty($param['total_amount'])) {
            return responseNotice("订单总金额不能为空");
        }
        if (empty($param['out_trade_no'])) {
            return responseNotice("商户订单号不能为空");
        }
        if (empty($param['subject'])) {
            return responseNotice("测试订单");
        }
        $useCodeClass = new AppPay;
        $useCodeClass->setBizContent($param);
        return $this->reqeustApi($useCodeClass,$authToken);
    }


     /**
     *  查询口碑交易订单
     * 
     */
    public function tradeQuery(array $param,string $authToken=null)
    {
        if (empty($param['order_no'])) {
            return responseNotice("商户订单号不能为空");
        }
        $useCodeClass = new \Support\Alipay\Request\KoubeiTradeQuery;
        $useCodeClass->setBizContent($param);
        return $this->reqeustApi($useCodeClass,$authToken);
    }
     /**
     *  获取商户下的门店列表
     * 
     */
    public function getAppMerchatList(array $param,$authToken=null)
    {
        $param['page_no']=!empty($param['page_no'])?$param['page_no']:1;

        $useCodeClass = new \Support\Alipay\Request\GetMerchantList;
        $useCodeClass->setBizContent($param);
        return $this->reqeustApi($useCodeClass,$authToken);
    }
    /**
     * 获取门店类目
     * 
     */
    public function getMerchantMenu(array $param,string $authToken=null)
    {
        $useCodeClass = new \Support\Alipay\Request\MerchantMenu;
        return $this->reqeustApi($useCodeClass,$authToken);
    }
    /**
     * 创建口碑门店商品
     * 
     */
    public function createMerchantGoods(array $params,string $authToken=null)
    {
        if(!$params['item_type']){
            return responseNotice("没有找到交易类型参数");
        }
        if(!$params['subject']){
            return responseNotice("没有找到商品参数");
        }
        if(!$params['price_mode']){
            return responseNotice("商品的的类型不能为空");
        }
        if(!$params['shop_ids']){
            return responseNotice("缺少口碑商品适用门店");
        }
        if(!$params['gmt_start']){
            return responseNotice("缺少口碑商品开始售卖时间");
        }
        if(!$params['gmt_end']){
            return responseNotice("缺少口碑商品结束售卖时间");
        }
        if(!$params['cover']){
            return responseNotice("缺少商品首图");
        } 
        if(!$params['price']){
            return responseNotice("缺少商品价格");
        }
        $createReqeust = new \Support\Alipay\Request\KoubeiItemCreateRequest;
        $createReqeust->setBizContent($params);
        return $this->reqeustApi($createReqeust,$authToken);
    }
    

    /**
     *  
     *  
     */
    public function reqeustApi($request,$authToken=null)
    {
        $koubeiService = new Client;
        $koubeiService->appId = ""; 
        $koubeiService->rsaPrivateKeyFilePath =dirname(__FILE__).'/../cert/alipay_rsa_private_key.pem';
        $koubeiService->alipayrsaPublicKey="支付宝公钥";
	$koubeiService->signType="RSA2";
        //处理口碑响应数据
        $handRes=(array)$koubeiService->execute($request,null,$authToken);
        $handRes=array_values($handRes);
        $handRes=(array)$handRes[0];
        return response($handRes);
    }


}
