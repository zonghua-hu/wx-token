<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/8/12
 * Time: 18:02
 */

namespace App\Domain\Tokens;

use App\Foundation\Repository\TokenRepository;
use WecarSwoole\Client\API;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;

/**
 * 开发者模式相关业务
 * Class DeveloperToken
 * @package App\Domain\Tokens
 */
class DeveloperToken extends CommonOperation
{
    public $appId;
    public $appSecret;
    public $cacheKey;

    /**
     * DeveloperToken constructor.
     * @param $appId
     * @param $appSecret
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \Exception
     */
    public function __construct($appId,$appSecret)
    {
        parent::__construct(CacheInterface::class, LoggerInterface::class,TokenRepository::class);
        $this->appId = $appId;
        $this->appSecret = $appSecret;
        $this->cacheKey = $this->appId.$this->key;
        $this->freshToken();
    }

    /**
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \Exception
     */
    private function freshToken()
    {
        $paramsToken = [
            'appId' => $this->appId,
            'appSecret' => $this->appSecret,
        ];
        $result = API::invoke('wechat:developer_token.get', $paramsToken);
        $accessToken = $result->getBody();
        if ($accessToken['status'] != 200) {
            $this->appAccessToken = false;
            $this->logger->info(">>>开发者模式：获取最新token失败".$this->appId."原因：".json_encode($accessToken));
        }
        $this->appAccessToken = $accessToken['access_token'];
        if ($this->appAccessToken) {
            $this->cache->set($this->cacheKey,$this->appAccessToken);
        }
    }
}
