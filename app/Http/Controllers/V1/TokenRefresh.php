<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/8/6
 * Time: 17:01
 */
namespace App\Http\Controllers\V1;

use WecarSwoole\Http\Controller;

/**
 * access_token相关控制器
 * Class TokenRefresh
 * @package App\Http\Controllers\V1
 */
class TokenRefresh extends Controller
{
    public function getToken()
    {
        $request_data = $this->params();
        $res = new TokenModel($request_data);



        
    }

}