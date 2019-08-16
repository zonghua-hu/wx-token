<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/8/7
 * Time: 17:31
 */

namespace App\Domain\Tokens;

use App\Foundation\Repository\TokenRepository;
use Exception;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;
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
     * TokenService constructor.
     * @param $appData
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws Exception
     */
    public function __construct($appData)
    {
        parent::__construct(CacheInterface::class, LoggerInterface::class, TokenRepository::class);

        $this->appId = $appData['appId'];
        $this->cacheKey = $this->appId . $this->key;
        $this->initToken();
    }

    /**
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws Exception
     */
    private function initToken()
    {
        $this->appAccessToken = $this->cache->get($this->cacheKey);
        if (!$this->appAccessToken) {
            $this->appAccessToken = self::forceFreshToken();
        }
    }

    /**
     * 刷新token
     * @throws Exception
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    private function forceFreshToken()
    {
        $appConfig = $this->tokenResp->getAppIdInfo($this->appId);
        if (!$appConfig) {
            throw new Exception('获取' . $this->appId . '商户信息出错', 402);
        }
        $appIdSecret = $appConfig['app_secret'];
        $tokenType = isset($appConfig['pattern']) ? $appConfig['pattern'] : 1;
        new TokenPattern($this->appId, $appIdSecret, $tokenType);
    }
}
