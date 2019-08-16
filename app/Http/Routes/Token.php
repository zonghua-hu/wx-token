<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/8/6
 * Time: 16:55
 */

namespace App\Http\Routes;


use WecarSwoole\Http\Route;

class Token extends Route
{
    public function map()
    {
        /**
         * 获取access_token接口
         * 接收参数：appId
         * 返回当前的：access_token
         */
        $this->post('/v1/tokens', '/V1/TokenRefresh/getToken');
    }

}