<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/8/8
 * Time: 17:06
 */

namespace App\Foundation\Repository;


class TokenRepository
{
    /**
     * 保存开放平台模式的token方法
     * @param $appData
     * @return mixed
     */
    public function saveComToken($appData)
    {
        return DB('wei_wx_component_access_token')->insert($appData);
    }

    /**
     * 保存开发者模式的token
     * @param $appData
     * @return mixed
     */
    public function saveDevToken($appData)
    {
        return DB('wei_wx_component_authorizer_token')->insert($appData);
    }

    public function getComponentTicket()
    {
        $ticket_info = D("WxComponent")->where(["info_type" => "component_verify_ticket"])->order("id desc")->find();
        if (!$ticket_info) {
            return false;
        }
        return $ticket_info;
    }



    public function getToken($appid)
    {
        return DB('wei_wx_component_authorizer_token')->where(['app_id' => $appid, 'is_delete' => 0,])->order("id desc")->find();
    }

    public function getOpenPlaformToken()
    {
        return D("WxComponentAccessToken")->where([])->order("id desc")->find();
    }

    /**
     * 获取全部appId信息
     * @return mixed
     */
    public function getAllAppConfig()
    {
        $sql = "select distinct from wei_wx_merchant_info where isvalid = 1";
        return $this->query($sql);
    }


    /**
     * 获取单站下的appid信息
     * @param $merchantInfo
     * @return mixed
     */
    public function getStationMerchantInfo($merchantInfo)
    {
        $where = [
            "ostn_id" => $merchantInfo,
            "isvalid" =>1
        ];
        $tokenData = DB("Wx_merchant_info")->where($where)->find();
        if (!$tokenData) {
            return false;
        }
        return $tokenData;
    }

    /**
     * 获取集团下的appid信息
     * @param $merchantInfo
     * @return mixed
     */
    public function getGroupMerchantInfo($merchantInfo)
    {
        $where = [
            "group_id" => $merchantInfo,
            "isvalid" =>1
        ];
        $tokenData =  DB("Wx_merchant_info")->where($where)->find();
        if (!$tokenData) {
            return false;
        }
        return $tokenData;
    }

    public function getAppIdInfo($appid)
    {
        $where = [
            "app_id" => $appid,
            "isvalid" =>1
        ];
        $appInfo = M("Wx_merchant_info")->where($where)->find();
        if (!$appInfo) {
            return false;
        }
        return  $appInfo;
    }

}
