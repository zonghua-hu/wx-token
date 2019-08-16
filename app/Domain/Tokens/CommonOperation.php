<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/8/14
 * Time: 16:10
 */

namespace App\Domain\Tokens;

use App\Foundation\Repository\TokenRepository;
use Exception;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;

abstract class CommonOperation
{
    /**
     * @var LoggerInterface
     */
    public $logger;
    /**
     * @var CacheInterface
     */
    public $cache;
    /**
     * @var string
     */
    public $key;
    /**
     * @var TokenRepository
     */
    public $tokenResp;
    /**
     * 最终的accessToken
     * @var
     */
    public $appAccessToken;
    /**
     * 开放平台模式
     * @var int
     */
    public $openPlatform = 1;
    /**
     * 开发者模式
     * @var int
     */
    public $developerToken = 2;
    /**
     * 喂车车appId
     * @var string
     */
    public $comAppId = 'wx46990617c3d8bf81';
    /**
     *喂车车appSecret
     * @var string
     */
    public $comAppSecret = '615d70ee3463062891771cfb1b4f7ef3';
    /**
     * 开放平台刷新token
     * @var
     */
    public $comAccessToken;
    /**
     * 微信推送令牌
     * @var bool|mixed
     */
    public $comTicket;

    /**
     * Common constructor.
     * @param CacheInterface $cache
     * @param LoggerInterface $logger
     * @param TokenRepository $tokenRepository
     * @throws Exception
     */
    public function __construct(CacheInterface $cache, LoggerInterface $logger, TokenRepository $tokenRepository)
    {
        $this->logger = $logger;
        $this->cache = $cache;
        $this->tokenResp = $tokenRepository;
        $this->key = 'wccRedisToken';
        $this->comTicket = $this->getTicket();
    }

    /**
     * 返回最终的accessToken
     * @return mixed
     */
    public function returnAccessToken()
    {
        return $this->appAccessToken;
    }

    /**
     * 获取第三方刷新token的令牌ticket
     * @return bool|mixed
     * @throws \Exception
     */
    public function getTicket()
    {
        $ticketConfig = $this->tokenResp->getComponentTicket();
        if (!$ticketConfig) {
            throw new Exception('获取comTicket出错', 401);
        }
        return str_replace("ticket@@@", "", $ticketConfig['component_verify_ticket']);
    }
}
