<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/8/15
 * Time: 12:01
 */
namespace App\Cron;

use Exception;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;
use App\Domain\Tokens\CommonOperation;
use App\Domain\Tokens\DeveloperToken;
use App\Domain\Tokens\OpenPlatformToken;
use App\Foundation\Repository\TokenRepository;

class TokenAutoFresh extends CommonOperation
{
    /**
     * TokenAutoFresh constructor.
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws Exception
     */
    public function __construct()
    {
        parent::__construct(CacheInterface::class, LoggerInterface::class, TokenRepository::class);
        $this->run();
    }

    /**
     * 1.从缓存中获取token，
     * 2.未获取到，则强刷token
     * 3.判断当前模式
     * 4.同模式token刷新实现类不同，获取到存进缓存
     * 5.返回当前的token
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \Exception
     */
    private function run()
    {
        $appConfig = $this->tokenResp->getAllAppConfig();
        if (!$appConfig) {
            throw new Exception('获取全部商户信息出错', 403);
        }
        foreach ($appConfig as $appInfo) {
            $appId = $appInfo['app_id'];
            $appSecret =  $appInfo['app_secret'];
            $appPattern = isset($appInfo['pattern']) ? $appInfo['pattern'] : 1;

            $this->appAccessToken = $this->cache->get($appId . $this->key);
            if (!$this->appAccessToken) {
                $resultToken = $this->freshToken($appId, $appSecret, $appPattern);
                if (!$resultToken) {
                    continue;
                }
            }
            $this->logger->info("当前商户" . $appId . "accessToken处于有效期，暂不刷新，Token值为" . $this->appAccessToken);
        }
    }
    /**
     * 刷新token
     * @param $appId
     * @param $appSecret
     * @param $appPattern
     * @return bool
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \Exception
     */
    private function freshToken($appId, $appSecret, $appPattern)
    {
        if ($appPattern == $this->openPlatform) {
            $this->appAccessToken = new OpenPlatformToken($appId);
            if (! $this->appAccessToken) {
                $this->logger->info(">>>开放平台模式：获取最新comAccessToken失败" . $appId);
                return false;
            }
        } else {
            $this->appAccessToken = new DeveloperToken($appId, $appSecret);
            if (! $this->appAccessToken) {
                $this->logger->info(">>>开发者模式：获取最新accessToken失败" . $appId);
                return false;
            }
        }
        return true;
    }
}
