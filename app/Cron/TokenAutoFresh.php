<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/8/15
 * Time: 12:01
 */
namespace App\Cron;

use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;
use App\Domain\Tokens\CommonOperation;
use App\Domain\Tokens\DeveloperToken;
use App\Domain\Tokens\OpenPlatformToken;
use App\Foundation\Repository\TokenRepository;

class TokenAutoFresh extends CommonOperation
{
    const OPEN_PLATFORM_TOKEN = 1;  //开放平台标识
    const DEVELOPER_TOKEN  = 2;    //开发者标识
    public $tokenResp;

    public function __construct()
    {
        parent::__construct(CacheInterface::class, LoggerInterface::class);
        $this->tokenResp = new TokenRepository();
    }

    /**
     * 1.从缓存中获取token，
     * 2.未获取到，则强刷token
     * 3.判断当前模式
     * 4.同模式token刷新实现类不同，获取到存进缓存
     * 5.返回当前的token
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function run()
    {
        $appConfig = $this->tokenResp->getAllAppConfig();
        foreach ($appConfig as $appInfo) {
            $appId = $appInfo['app_id'];
            $appSecret =  $appInfo['app_id'];
            $appPattern = isset($appInfo['pattern'])?$appInfo['pattern']:1;

            $accessToken = $this->cache->get($appId.$this->key);
            if (!$accessToken) {
                $resultToken = $this->freshToken($appId,$appSecret,$appPattern);
                if (!$resultToken) {
                    continue;
                }
            }
            $this->logger->info("当前商户".$appId."accessToken处于有效期，暂不刷新，Token值为".$accessToken);
        }
    }
    /**
     * 刷新token
     * @param $appId
     * @param $appSecret
     * @param $appPattern
     * @return bool
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    private function freshToken($appId,$appSecret,$appPattern)
    {
        if ($appPattern == self::OPEN_PLATFORM_TOKEN) {
            $appTicket = self::getTicket();
            if (!$appTicket) {
                $this->logger->info(">>>开放平台模式：获取最新componentTicker失败".$appId);
                return false;
            }
            $comAccessToken = new OpenPlatformToken($appId,$appTicket);
            if (!$comAccessToken) {
                $this->logger->info(">>>开放平台模式：获取最新comAccessToken失败".$appId);
                return false;
            }
        } else {
            $accessToken = new DeveloperToken($appId,$appSecret);
            if (!$accessToken) {
                $this->logger->info(">>>开发者模式：获取最新accessToken失败".$appId);
                return false;
            }
        }
        return true;
    }
    /**
     * 获取第三方刷新token的令牌ticket
     * @return bool|mixed
     */
    private function getTicket()
    {
        $ticketConfig = $this->tokenResp->getComponentTicket();
        if (!$ticketConfig) {
            return false;
        }
        return str_replace("ticket@@@", "", $ticketConfig['component_verify_ticket']);
    }
}