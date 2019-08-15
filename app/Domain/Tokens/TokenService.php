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
    private $openPlatform = 1;    //开放平台模式
    private $developerToken = 2;  //开发者模式

    public $appId;
    public $appIdSecret;
    public $accessToken = false;
    public $appTicket;

    public $tokenRepository;
    public $cacheKey;

    /**
     * TokenService constructor.
     * @param TokenRepository $tokenRepository
     * @param $appData
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function __construct(TokenRepository $tokenRepository, $appData)
    {
        parent::__construct(CacheInterface::class, LoggerInterface::class);
        $this->tokenRepository = $tokenRepository;
        $this->appId = $appData['appId'];
        $this->cacheKey = $this->appId.$this->key;
        $this->initToken();
    }
    /**
     * 获取accessToken
     * @return mixed
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }
    /**
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    private function initToken()
    {
        $this->accessToken = $this->cache->get($this->cacheKey);
        if (!$this->accessToken) {
            $this->accessToken = self::forceFreshToken();
        }
    }
    /**
     * 获取ticket
     * @throws Exception
     */
    private function getTicket()
    {
        $ticketConfig = $this->tokenRepository->getComponentTicket();
        if (!$ticketConfig) {
            throw new Exception('获取comTicket出错', 401);
        }
        $this->appTicket = str_replace("ticket@@@", "", $ticketConfig['component_verify_ticket']);
    }

    /**
     * 刷新token
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws Exception
     */
    private function forceFreshToken()
    {
        $appConfig = $this->tokenRepository->getAppIdInfo($this->appId);
        if (!$appConfig) {
            throw new Exception('获取'.$this->appId.'商户信息出错', 402);
        }
        $this->appIdSecret = $appConfig['app_secret'];
        $tokenType = isset($appConfig['pattern'])?$appConfig['pattern']:1;
        if ($tokenType == $this->developerToken) {
            $this->accessToken = new DeveloperToken($this->appId, $this->appIdSecret);
        } elseif ($tokenType == $this->openPlatform) {
            if (self::getTicket()) {
                $this->accessToken = new OpenPlatformToken($this->appId, $this->appTicket);
            }
        }
    }
}
