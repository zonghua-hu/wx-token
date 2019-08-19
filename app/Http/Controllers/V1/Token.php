<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/8/6
 * Time: 17:01
 */
namespace App\Http\Controllers\V1;

use App\Domain\Tokens\TokenService;
use App\ErrCode;
use WecarSwoole\Http\Controller;
use WecarSwoole\Container;


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
     * @throws \Throwable
     */
    public function getToken()
    {
        $appId = $this->params();
        $tokenService = Container::make(TokenService::class);
        $accessToken = $tokenService->getToken($appId);
        if (!$accessToken) {
            $this->return(ErrCode::FINAL_ACCESS_TOKEN_NULL, '获取accessToken失败,商户信息：' . $appId['appID']);
        }
        $this->return($accessToken, 200, 'succeed');
    }
}
