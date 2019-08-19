<?php

namespace App;

use WecarSwoole\ErrCode as BaseErrCode;

/**
 * Class ErrCode
 * 200 表示 OK
 * 500 以下为框架保留错误码，项目中不要用，项目中从 501 开始
 * @package App
 */
class ErrCode extends BaseErrCode
{
    public const COMPONENT_TOKEN_FAILED = 502;     //开放平台comToken获取失败
    public const DEV_ACCESS_TOKEN_FAILED = 503;    //开发者模式子商户accessToken获取失败
    public const MERCHANT_INFO_NULL = 504;         //获取商户配置信息失败
    public const COMPONENT_TICKET_NULL = 505;      //查询from数据库comTicket失败
    public const OPEN_ACCESS_TOKEN_FAILED = 506;   //开放平台模式子商户accessToken获取失败
    public const ALL_MERCHANT_INFO_NULL = 507;     //查询全部商户配置信息失败
    public const FINAL_ACCESS_TOKEN_NULL = 508;    //获取最终的token失败

}
