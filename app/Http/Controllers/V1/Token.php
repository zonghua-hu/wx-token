<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/8/6
 * Time: 17:01
 */
namespace App\Http\Controllers\V1;

use App\Domain\Tokens\TokenService;
use WecarSwoole\Http\Controller;


/**
 * access_token相关控制器
 * Class TokenRefresh
 * @package App\Http\Controllers\V1
 */
class Token extends Controller
{
    /**
     * 参数校验
     * @return array
     */
    protected function validateRules(): array
    {
        return [
            'info' => [
                'appId'   => ['required', 'integer']
            ]
        ];
    }

    /**
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function getToken()
    {
        $appId = $this->params();
        $tokenService = new TokenService($appId);
        $accessToken = $tokenService->returnAccessToken();
        if (!$accessToken) {
            $this->return('',10086,'获取accessToken失败~',2);
        }
        $this->return($accessToken,200,'succeed');
    }
}
