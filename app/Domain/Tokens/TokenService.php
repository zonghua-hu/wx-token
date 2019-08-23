<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/8/7
 * Time: 17:31
 */

namespace App\Domain\Tokens;

use App\ErrCode;
use WecarSwoole\Container;
use Exception;
/**
 * 业务逻辑：---------------------------------------------------
 * 1.初始化拿到appId。
 * 2.去缓存中取当前的accessToken。
 * 3.若取到直接返回当前accessToken.
 * 4.未取到则强制刷新token并存进缓存。
 * 业务逻辑：---------------------------------------------------
 *
 * Class TokenService
 * @package App\Domain\Tokens
 */
class TokenService extends CommonOperation
{
    public $appId;
    public $cacheKey;
    /**
     * @param $appId
     * @return mixed
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \Throwable
     */
    public function getToken($appId)
    {
        $this->appId = $appId['appId'];
        $this->cacheKey = $this->appId . $this->key;
        $this->appAccessToken = $this->cache->get($this->cacheKey);
        if (!$this->appAccessToken) {
            $this->appAccessToken = self::forceFreshToken();
        }
        return $this->returnAccessToken();
    }

    /**
     * @throws Exception
     * @throws \Throwable
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    private function forceFreshToken()
    {
        $appConfig = $this->tokenResp->getAppIdInfo($this->appId);
        if (!$appConfig) {
            throw new Exception('获取' . $this->appId . '商户信息出错', ErrCode::MERCHANT_INFO_NULL);
        }
        $appIdSecret = $appConfig['app_secret'];
        $tokenType = isset($appConfig['pattern']) ? $appConfig['pattern'] : 1;
        $tokenPattern = Container::make(TokenPattern::class);
        $tokenPattern->freshDifferentToken($this->appId, $appIdSecret, $tokenType);
    }
}
