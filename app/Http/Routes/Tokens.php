<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/8/6
 * Time: 16:55
 */

namespace App\Http\Routes;


use WecarSwoole\Http\Route;

class Tokens extends Route
{
    public function map()
    {
        //获取access_token接口
        $this->post('/v1/tokens','/V1/TokenRefresh/getToken');
    }

}